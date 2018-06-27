<?php

namespace Uhin\X12Parser\EDI;

use JsonSerializable;

class X12 implements JsonSerializable
{

    /** @var array */
    public $ISA = [];

    // TODO: add some lookup/search functions
    // TODO: add setters?
    // TODO: add some splitters?

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            "EDI-X12" => $this->ISA,
        ];
    }

}