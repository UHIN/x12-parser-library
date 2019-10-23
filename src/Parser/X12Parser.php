<?php

namespace Uhin\X12Parser\Parser;

use Exception;
use Uhin\X12Parser\EDI\Segments\Segment;
use Uhin\X12Parser\EDI\X12;
use Uhin\X12Parser\EDI\Segments\GS;
use Uhin\X12Parser\EDI\Segments\HL;
use Uhin\X12Parser\EDI\Segments\ISA;
use Uhin\X12Parser\EDI\Segments\ST;

class X12Parser
{

    /** @var StringTokenizer */
    private $reader;

    /**
     * X12Parser constructor.
     * @param string $rawX12
     */
    public function __construct($rawX12)
    {
        $this->reader = new StringTokenizer(trim(str_replace(["\n", "\t", "\r"], '', $rawX12)));
    }

    /**
     * Attempts to parse an X12 into PHP X12 objects. This should return an instance
     * of `X12`, or throw an exception upon failure.
     *
     * @return X12
     * @throws Exception
     */
    public function parse()
    {
        // Logging
        $logging = false;
        $startTime = microtime(true);
        $segmentCount = 0;
        if ($logging) {
            echo "Beginning parsing of file: {$this->formatBytes($this->reader->getStringLength())} bytes.\r\n";
        }

        // Define some things for parsing the file
        $x12 = new X12();
        $segmentDelimiter = null;
        $dataElementDelimiter = null;
        $repetitionDelimiter = null;
        $subRepetitionDelimiter = null;

        // We need to parse the delimiters of the first ISA before we can do anything
        if (!$this->parseDelimiters($segmentDelimiter, $dataElementDelimiter, $repetitionDelimiter, $subRepetitionDelimiter)) {
            throw new Exception('Could not parse the delimiters of the first ISA.');
        }

        // Read through the segments in the file
        while (($segmentString = $this->reader->next($segmentDelimiter)) !== false) {

            // Check for an empty string
            if (strlen($segmentString) <= 0) {
                continue;
            }

            // Get the data elements in this segment
            $dataElements = explode($dataElementDelimiter, $segmentString);

            // Check for repetition delimiters... If there are any, split the element into an array
            for ($i = 1; $i < count($dataElements); $i++) {
                if ($dataElements[0] === 'ISA' && ($i === 11 || $i === 16)) {
                    // Skip this check for ISA11 and ISA16
                    continue;
                }
                if (strpos($dataElements[$i], $repetitionDelimiter) !== false) {
                    $dataElements[$i] = explode($repetitionDelimiter, $dataElements[$i]);

                    // Check for sub-repetition delimiters... If there are any, split the sub element into an array
                    for ($j = 0; $j < count($dataElements[$i]); $j++) {
                        if (strpos($dataElements[$i][$j], $subRepetitionDelimiter) !== false) {
                            $dataElements[$i][$j] = explode($subRepetitionDelimiter, $dataElements[$i][$j]);
                        }
                    }
                }

                // Check for any lingering sub-repetition elements (this happens when you have sub-repetition elements with only one parent repetition element)
                if (is_string($dataElements[$i]) && strpos($dataElements[$i], $subRepetitionDelimiter) !== false) {
                    $dataElements[$i] = [explode($subRepetitionDelimiter, $dataElements[$i])];
                }
            }

            // Handle the parsed segment
            switch ($dataElements[0]) {

                case 'ISA':
                    $x12->ISA[] = new ISA($dataElements, $segmentDelimiter, $dataElementDelimiter, $repetitionDelimiter, $subRepetitionDelimiter);
                    break;


                case 'IEA':
                    /** @var ISA $isa */
                    $isa = end($x12->ISA);

                    // Add this IEA to the ISA
                    $isa->IEA = new Segment($dataElements);

                    // Try to re-parse the delimiters, in case this is a second ISA in the same file
                    if (!$this->parseDelimiters($segmentDelimiter, $dataElementDelimiter, $repetitionDelimiter, $subRepetitionDelimiter)) {

                        // if delimiters couldn't be found... we should be done parsing this file
                        break;
                    }
                    break;


                case 'GS':
                    /** @var ISA $isa */
                    $isa = end($x12->ISA);

                    // Add this GS to the ISA
                    $isa->GS[] = new GS($dataElements);
                    break;


                case 'GE':
                    /** @var ISA $isa */
                    $isa = end($x12->ISA);

                    /** @var GS $gs */
                    $gs = end($isa->GS);

                    // Add this GE to the GS
                    $gs->GE = new Segment($dataElements);
                    break;


                case 'ST':
                    /** @var ISA $isa */
                    $isa = end($x12->ISA);

                    /** @var GS $gs */
                    $gs = end($isa->GS);

                    // Add this ST to the GS
                    $gs->ST[] = new ST($dataElements);
                    break;


                case 'SE':
                    /** @var ISA $isa */
                    $isa = end($x12->ISA);

                    /** @var GS $gs */
                    $gs = end($isa->GS);

                    /** @var ST $st */
                    $st = end($gs->ST);

                    // Add this SE to the ST
                    $st->SE = new Segment($dataElements);
                    break;


                case 'HL':
                    /** @var ISA $isa */
                    $isa = end($x12->ISA);

                    /** @var GS $gs */
                    $gs = end($isa->GS);

                    /** @var ST $st */
                    $st = end($gs->ST);

                    // Create the HL segment
                    $hl = new HL($dataElements);

                    // Determine which ST or HL segment this HL belongs to, and then add it
                    $hlParentId = '';
                    if ($hl->HL02) {
                        $hlParentId = trim($hl->HL02);
                    }
                    $parent = $st;
                    if (strlen($hlParentId) > 0) {
                        $parent = $this->findHLParent($st->HL, $hlParentId);
                        if ($parent === null) {
                            throw new Exception("HL parent with ID of {$hlParentId} could not be found.");
                        }
                    }
                    $parent->HL[] = $hl;

                    break;


                case 'TA1':
                    /** @var ISA $isa */
                    $isa = end($x12->ISA);

                    // Add this TA1 to the ISA
                    $isa->TA1 = new Segment($dataElements);
                    break;


                default:
                    // This is some other segment that we don't account for above, so handle it now...
                    $segment = new Segment($dataElements);
                    $this->addSegmentAttribute($x12, $segment);
                    break;
            }

            // Logging
            $segmentCount++;
            if ($logging) {
                if ($segmentCount % 5000 === 0) {
                    $elapsed = microtime(true) - $startTime;
                    $percentComplete = round($this->reader->getCompletionPercent() * 100, 3);
                    echo "Parsed {$percentComplete}% of file, {$this->formatSeconds($elapsed)} elapsed...\r\n";
                }
            }

            // Garbage cleanup
            if ($segmentCount % 10000 === 0) {
                gc_collect_cycles();
            }

        }

        // Logging
        if ($logging) {
            $now = microtime(true);
            $elapsed = round($now - $startTime, 3);
            echo "Finished parsing {$this->formatBytes($this->reader->getStringLength())} file ({$segmentCount} segments) in {$elapsed} seconds.\r\n";
        }

        // Return the parsed X12
        return $x12;
    }

    /**
     * Parses out the delimiters from the current position in the file reader. This
     * assumes that the next line to be read is an ISA line. If it's not, or if this
     * function fails to parse out the delimiters, then `false` will be returned.
     * On success, this function will return true and set all of the delimiters.
     *
     * @param $segmentDelimiter
     * @param $dataElementDelimiter
     * @param $repetitionDelimiter
     * @param $subRepetitionDelimiter
     * @return bool
     */
    private function parseDelimiters(&$segmentDelimiter, &$dataElementDelimiter, &$repetitionDelimiter, &$subRepetitionDelimiter)
    {
        // The spec is supposed to be:
        // The repetition separator is byte 83
        // The component element separator is byte 105
        // The segment terminator is the byte that immediately follows the component element separator
        //
        // But we get less errors parsing X12 files if we just parse out the delimiters from the ISA segment instead...

        // Check if we're at the end of the file
        if ($this->reader->isDone()) {
            return false;
        }

        // Determine what position we're at in the file
        $position = $this->reader->getPosition();

        // Check for a new ISA
        if ($this->reader->getSubstring($position, 3) !== 'ISA') {
            return false;
        }

        // Get the data element delimiter
        $dataEl = $this->reader->getSubstring($position + 3, 1);

        // Check if the data element delimiter is valid
        if (strlen($dataEl) !== 1) {
            return false;
        }

        // Read in the next 18 data elements (the entire ISA line, plus the next element)
        $dataElements = [];
        $element = null;
        while (($element = $this->reader->next($dataEl)) !== false) {
            $dataElements[] = $element;
            if (count($dataElements) >= 18) {
                break;
            }
        }

        // Check if the ISA was properly formatted
        if (count($dataElements) < 17) {
            return false;
        }
        if (strlen($dataElements[16]) < 2) {
            return false;
        }

        // Get the other delimiters from the ISA line
        $rep = $dataElements[11];
        $subRep = $dataElements[16][0];
        $segment = $dataElements[16][1];

        // Reset the reader to the correct position (immediately after the ISA line)
        $this->reader->setPosition($position);

        // Check if all the delimiters are valid before setting any of them
        if (
            strlen($dataEl) === 1
            && strlen($rep) === 1
            && strlen($subRep) === 1
            && strlen($segment) === 1
        ) {
            // These are set by reference, so the variables passed to this function get set here
            $segmentDelimiter = $segment;
            $dataElementDelimiter = $dataEl;
            $repetitionDelimiter = $rep;
            $subRepetitionDelimiter = $subRep;
            return true;
        }

        return false;
    }

    /**
     * @param array $hlSegments
     * @param string $parentId
     * @return HL
     */
    private function findHLParent(&$hlSegments, &$parentId)
    {
        /** @var HL $hlSegment */
        foreach ($hlSegments as &$hlSegment) {
            if (trim($hlSegment->HL01) === $parentId) {
                return $hlSegment;
            }
            $parent = $this->findHLParent($hlSegment->HL, $parentId);
            if ($parent !== null) {
                return $parent;
            }
        }
        return null;
    }

    /**
     * Adds a generic segment (one that we might not specifically handle) to the X12
     * object. This will place the segment into the right location of the data
     * structure.
     *
     * @param X12 $x12
     * @param Segment $segment
     */
    private function addSegmentAttribute(&$x12, &$segment)
    {
        /** @var ISA $isa */
        $isa = end($x12->ISA);

        /** @var GS $gs */
        $gs = end($isa->GS);

        /** @var ST $st */
        $st = end($gs->ST);

        // Check if this segment belongs to an HL segment
        if (count($st->HL) > 0) {
            /** @var HL $hl */
            $hl = end($st->HL);

            // Recursively check for any nested HL segments
            while (count($hl->HL) > 0) {
                /** @var HL $hl */
                $hl = end($hl->HL);
            }

            // Add the segment to the last HL
            $hl->properties[] = $segment;

        } else {
            // Otherwise, this should belong to the ST segment
            $st->properties[] = $segment;
        }
    }

    /**
     * Formats the given number of bytes into a human readable string.
     *
     * @param $bytes
     * @return string
     */
    private function formatBytes($bytes)
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        } else if ($bytes < (1024 * 1024)) {
            return round($bytes / 1024, 1) . ' KB';
        } else if ($bytes < (1024 * 1024 * 1024)) {
            return round($bytes / (1024 * 1024), 2) . ' MB';
        } else {
            return round($bytes / (1024 * 1024 * 1024), 3) . ' GB';
        }
    }

    /**
     * Formats the given number of seconds into a human readable string.
     *
     * @param $seconds
     * @return string
     */
    private function formatSeconds($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = round(fmod($seconds, 60), 3);
        if ($hours <= 0) {
            if ($minutes <= 0) {
                return "{$seconds} sec";
            } else {
                return "{$minutes} min {$seconds} sec";
            }
        } else {
            return "{$hours} hrs {$minutes} min {$seconds} sec";
        }
    }

}