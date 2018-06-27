<?php

namespace Uhin\X12Parser\Parser;

use Exception;
use Uhin\X12Parser\EDI\X12;

class XmlParser
{

    /** @var string */
    private $rawXml;

    /**
     * XmlParser constructor.
     * @param string $rawXml
     */
    public function __construct($rawXml)
    {
        $this->rawXml = $rawXml;
    }

    /**
     * Attempts to parse an Xml formatted EDI into PHP X12 objects. This should return an instance
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