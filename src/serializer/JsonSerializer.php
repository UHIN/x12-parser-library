<?php

namespace Uhin\X12Parser\Serializer;

use Uhin\X12Parser\EDI\X12;

class JsonSerializer
{

    /** @var X12 */
    protected $x12;

    /** @var integer */
    private $encodingOptions;

    public function __construct(X12 &$x12)
    {
        $this->x12 = $x12;
        $this->encodingOptions = 0;
    }

    /**
     * Sets the encoding options that will be passed to json_encode.
     *
     * @param $encodingOptions integer
     */
    public function setEncodingOptions($encodingOptions)
    {
        $this->encodingOptions = $encodingOptions;
    }

    /**
     * Converts the X12 object to an Xml document.
     *
     * @return string
     */
    public function serialize()
    {
        return json_encode($this->x12, $this->encodingOptions);
    }

}