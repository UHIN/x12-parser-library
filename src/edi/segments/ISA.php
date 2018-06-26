<?php

namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class ISA
 *
 * @property-read string ISA01 Authorization Information Qualifier
 * @property-read string ISA02 Authorization Information
 * @property-read string ISA03 Security Information Qualifier
 * @property-read string ISA04 Security Information
 * @property-read string ISA05 Interchange ID Qualifier
 * @property-read string ISA06 Interchange Sender ID
 * @property-read string ISA07 Interchange ID Qualifier
 * @property-read string ISA08 Interchange Receiver ID
 * @property-read string ISA09 Interchange Date
 * @property-read string ISA10 Interchange Time
 * @property-read string ISA11 Interchange Control Standards ID
 * @property-read string ISA12 Interchange Control Version Number
 * @property-read string ISA13 Interchange Control Number
 * @property-read string ISA14 Acknowledgement Requested
 * @property-read string ISA15 Test Indicator
 * @property-read string ISA16 Sub Element Separator
 *
 * @package Uhin\X12Parser
 */
class ISA extends Envelope
{

    /** @var Segment */
    public $IEA;

    /** @var Segment */
    public $TA1;

    /** @var array */
    public $GS = [];

    /** @var string */
    private $segmentDelimiter;

    /** @var string */
    private $dataElementDelimiter;

    /** @var string */
    private $repetitionDelimiter;

    /** @var string */
    private $subRepetitionDelimiter;

    /**
     * ISA constructor.
     *
     * @param $dataElements
     * @param $segmentDelimiter
     * @param $dataElementDelimiter
     * @param $repetitionDelimiter
     * @param $subRepetitionDelimiter
     */
    public function __construct($dataElements, $segmentDelimiter, $dataElementDelimiter, $repetitionDelimiter, $subRepetitionDelimiter)
    {
        // For ISAs, we need to save the delimiters so that we can use them for gluing later
        $this->segmentDelimiter = $segmentDelimiter;
        $this->dataElementDelimiter = $dataElementDelimiter;
        $this->repetitionDelimiter = $repetitionDelimiter;
        $this->subRepetitionDelimiter = $subRepetitionDelimiter;
        parent::__construct($dataElements);
    }

    public function getSegmentDelimiter()
    {
        return $this->segmentDelimiter;
    }

    public function getDataElementDelimiter()
    {
        return $this->dataElementDelimiter;
    }

    public function getRepetitionDelimiter()
    {
        return $this->repetitionDelimiter;
    }

    public function getSubRepetitionDelimiter()
    {
        return $this->subRepetitionDelimiter;
    }

}