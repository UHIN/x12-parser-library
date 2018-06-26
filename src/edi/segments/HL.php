<?php

namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class HL
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property-read string HL01 Hierarchical ID Number
 * @property-read string HL02 Hierarchical Parent ID Number
 * @property-read string HL03 Hierarchical Level Code
 * @property-read string HL04 Hierarchical Child Code
 */
class HL extends Envelope
{

    /** @var array */
    public $HL = [];

    /** @var array */
    public $properties = [];

}