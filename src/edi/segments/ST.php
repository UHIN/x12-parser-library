<?php

namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class ST
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string ST01 Transaction Set Identifier Code
 * @property string ST02 Transaction Set Control Number
 * @property string ST03 Implementation Convention Reference
 */
class ST extends Segment
{

    /** @var Segment */
    public $SE;

    /** @var array */
    public $HL = [];

    /** @var array */
    public $properties = [];

}