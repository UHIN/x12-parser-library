<?php

namespace Uhin\X12Parser\EDI;

use JsonSerializable;

class X12 implements JsonSerializable
{

    /** @var array */
    public $ISA = [];

    public function getDataElement($filter)
    {
        // TODO: implement
    }

    public function getSegment($filter)
    {
        // TODO: implement
    }

    // TODO: add some lookup/filter functions
    // TODO: add setters?
    // TODO: add splitting helpers?

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): mixed
    {
        return [
            "EDI-X12" => $this->ISA,
        ];
    }

}