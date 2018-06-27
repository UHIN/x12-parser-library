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
class HL extends Segment
{

    /** @var array */
    public $HL = [];

    /** @var array */
    public $properties = [];

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        // Serialize the data elements
        $serialized = parent::jsonSerialize();

        // Properties
        if (count($this->properties) > 0) {
            $serialized["properties"] = $this->properties;
        }

        // HL
        if (count($this->HL) > 0) {
            $serialized["HL"] = $this->HL;
        }

        return $serialized;
    }

}