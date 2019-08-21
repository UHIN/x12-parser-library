<?php

namespace Uhin\X12Parser\Serializer;

use Uhin\X12Parser\EDI\Segments\GS;
use Uhin\X12Parser\EDI\Segments\HL;
use Uhin\X12Parser\EDI\Segments\ISA;
use Uhin\X12Parser\EDI\Segments\Segment;
use Uhin\X12Parser\EDI\Segments\ST;
use Uhin\X12Parser\EDI\X12;

class XmlSerializer
{

    /** @var X12 */
    protected $x12;

    public function __construct(X12 &$x12)
    {
        $this->x12 = $x12;
    }

    /**
     * Converts the X12 object to an Xml document.
     *
     * @return string
     */
    public function serialize()
    {
        $output = '<?xml version="1.0" ?>';
        $output .= "<EDI-X12>";

        /** @var ISA $isa */
        foreach ($this->x12->ISA as &$isa) {

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
        }

        $output .= "</EDI-X12>";

        // Return the serialized data
        return $output;
    }


    /**
     * Serializes the given segment using the current ISA's delimiters.
     *
     * @param Segment $segment
     * @return string
     */
    private function serializeSegment(&$segment)
    {
        // Opening tag
        $segmentId = $segment->getSegmentId();
        $output = "<{$segmentId}>";

        // Traverse over the data elements
        $elements = $segment->getDataElements();
        for ($element = 1; $element < count($elements); $element++) {

            // Generate a segment name that looks something like "GS02"
            $elementName = $segmentId . sprintf("%02d", $element);
            $output .= "<{$elementName}>";

            // Check if this element has any repetition elements
            if (is_array($elements[$element])) {
                for ($repetitionElement = 0; $repetitionElement < count($elements[$element]); $repetitionElement++) {

                    // Create a repetition element that looks something like "<GS02-0>", "<GS02-1>", etc.
                    $output .= "<{$elementName}-{$repetitionElement}>";

                    // Check if this repetition element has any sub-repetition elements
                    if (is_array($elements[$element][$repetitionElement])) {
                        for ($subRepetitionElement = 0; $subRepetitionElement < count($elements[$element][$repetitionElement]); $subRepetitionElement++) {

                            // Add the sub-repetition element with a format like "<GS02-0-0>", "<GS02-0-1>", etc.
                            $output .= "<{$elementName}-{$repetitionElement}-{$subRepetitionElement}>";
                            $output .= $elements[$element][$repetitionElement][$subRepetitionElement];
                            $output .= "</{$elementName}-{$repetitionElement}-{$subRepetitionElement}>";
                        }
                    } else {
                        $output .= $elements[$element][$repetitionElement];
                    }
                    $output .= "</{$elementName}-{$repetitionElement}>";
                }

            } else {
                // Add the element to the output
                $output .= $elements[$element];
            }

            // Close the element tag
            $output .= "</{$elementName}>";
        }

        // Closing tag
        $output .= "</{$segment->getSegmentId()}>";

        return $output;
    }

    private function serializeHLSegment(&$hl)
    {
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