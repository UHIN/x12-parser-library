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

        // SE
        $serialized["SE"] = $this->SE;

        return $serialized;
    }

}