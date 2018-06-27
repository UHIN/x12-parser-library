<?php

namespace Uhin\X12Parser\Parser;

use Exception;
use Uhin\X12Parser\EDI\X12;

class JsonParser
{

    /** @var string */
    private $rawJson;

    /**
     * JsonParser constructor.
     * @param string $rawJson
     */
    public function __construct($rawJson)
    {
        $this->rawJson = $rawJson;
    }

    /**
     * Attempts to parse a Json formatted EDI into PHP X12 objects. This should return an instance
     * of `X12`, or throw an exception upon failure.
     *
     * @return X12
     * @throws Exception
     */
    public function parse()
    {
        // TODO: implement
        return null;
    }

}