<?php

namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class ST
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property-read string ST01 Transaction Set Identifier Code
 * @property-read string ST02 Transaction Set Control Number
 * @property-read string ST03 Implementation Convention Reference
 */
class ST extends Envelope
{

    /** @var Segment */
    public $SE;

    /** @var array */
    public $HL = [];

    /** @var array */
    public $properties = [];

}