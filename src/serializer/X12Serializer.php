<?php

namespace Uhin\X12Parser\Serializer;

use Uhin\X12Parser\EDI\Segments\GS;
use Uhin\X12Parser\EDI\Segments\HL;
use Uhin\X12Parser\EDI\Segments\ISA;
use Uhin\X12Parser\EDI\Segments\Segment;
use Uhin\X12Parser\EDI\Segments\ST;
use Uhin\X12Parser\EDI\X12;

class X12Serializer extends Serializer
{

    /** @var bool */
    private $addNewLineAfterSegment;

    /** @var bool */
    private $addNewLineAfterIEA;

    /** @var string */
    private $segmentDelimiter;

    /** @var string */
    private $dataElementDelimiter;

    /** @var string */
    private $repetitionDelimiter;

    /** @var string */
    private $subRepetitionDelimiter;

    public function __construct(X12 &$x12)
    {
        $this->addNewLineAfterSegment = false;
        $this->addNewLineAfterIEA = false;
        parent::__construct($x12);
    }

    /**
     * Sets whether or not a new line character should be added to the end of
     * each segment.
     *
     * @param $addNewLineAfterSegment bool
     */
    public function addNewLineAfterSegment($addNewLineAfterSegment) {
        $this->addNewLineAfterSegment = $addNewLineAfterSegment;
    }

    /**
     * Sets whether or not a new line character should be added to the end of
     * each IEA segment.
     *
     * @param $addNewLineAfterIEA bool
     */
    public function addNewLineAfterIEA($addNewLineAfterIEA) {
        $this->addNewLineAfterIEA = $addNewLineAfterIEA;
    }

    /**
     * Converts the X12 object to an X12 EDI document.
     *
     * @return string
     */
    public function serialize()
    {
        $output = '';

        /** @var ISA $isa */
        foreach ($this->x12->ISA as &$isa) {
            // Get the delimiter definitions from the ISA segment
            $this->setDelimiters($isa);

            // ISA
            $output .= $this->serializeSegment($isa);

            // TA1
            if ($isa->TA1) {
                $output .= $this->serializeSegment($isa->TA1);
            }

            /** @var GS $gs */
            foreach ($isa->GS as &$gs) {

                // GS
                $output .= $this->serializeSegment($gs);

                /** @var ST $st */
                foreach ($gs->ST as &$st) {

                    // ST
                    $output .= $this->serializeSegment($st);

                    /** @var Segment $property */
                    foreach ($st->properties as &$property) {

                        // ST Property
                        $output .= $this->serializeSegment($property);
                    }

                    /** @var HL $hl */
                    foreach ($st->HL as &$hl) {

                        // HL
                        $output .= $this->serializeHLSegment($hl);
                    }

                    // SE
                    $output .= $this->serializeSegment($st->SE);
                }

                // GE
                $output .= $this->serializeSegment($gs->GE);
            }

            // IEA
            $output .= $this->serializeSegment($isa->IEA);

            // Add the new line after this segment, if necessary
            if ($this->addNewLineAfterIEA) {
                $output .= "\n";
            }
        }

        // Return the serialized data
        return $output;
    }

    /**
     * Gets the delimiter definitions from the given ISA segment.
     *
     * @param ISA $isa
     */
    private function setDelimiters(&$isa)
    {
        $this->segmentDelimiter = $isa->getSegmentDelimiter();
        $this->dataElementDelimiter = $isa->getDataElementDelimiter();
        $this->repetitionDelimiter = $isa->getRepetitionDelimiter();
        $this->subRepetitionDelimiter = $isa->getSubRepetitionDelimiter();
    }

    /**
     * Serializes the given segment using the current ISA's delimiters.
     *
     * @param Segment $segment
     * @return string
     */
    private function serializeSegment(&$segment)
    {
        // Iterate over the data elements array
        $elements = $segment->getDataElements();
        foreach ($elements as &$element) {

            // Check if this data element has any repetition elements... If so, concatenate them
            if (is_array($element)) {
                foreach ($element as &$repetitionElement) {

                    // Check if this repetition element has any sub-repetition elements... If so, concatenate them
                    if (is_array($repetitionElement)) {

                        // Concatenate the sub-repetition elements
                        $repetitionElement = implode($this->subRepetitionDelimiter, $repetitionElement);
                    }
                }

                // Concatenate the repetition elements
                $element = implode($this->repetitionDelimiter, $element);
            }
        }

        // Concatenate the data elements
        return implode($this->dataElementDelimiter, $elements) . $this->segmentDelimiter . ($this->addNewLineAfterSegment ? "\n" : "");
    }

    /**
     * Recursively serializes the given HL segment and all of its children
     * using the current ISA's delimiters.
     *
     * @param HL $hl
     * @return string
     */
    private function serializeHLSegment(&$hl) {

        // HL
        $output = $this->serializeSegment($hl);

        /** @var Segment $property */
        foreach ($hl->properties as &$property) {

            // HL Property
            $output .= $this->serializeSegment($property);
        }

        /** @var HL $child */
        foreach ($hl->HL as &$child) {

            // HL Child
            $output .= $this->serializeHLSegment($child);
        }

        // Return all output
        return $output;
    }

}