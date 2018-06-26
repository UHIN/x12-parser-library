<?php

namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class HL
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string HL01 Hierarchical ID Number
 * @property string HL02 Hierarchical Parent ID Number
 * @property string HL03 Hierarchical Level Code
 * @property string HL04 Hierarchical Child Code
 */
class HL extends Envelope
{

    /** @var array */
    public $HL = [];

    /** @var array */
    public $properties = [];

}