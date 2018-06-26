<?php

namespace Uhin\X12Parser\EDI\Segments;

use Exception;

class Segment
{

    /** @var array */
    protected $dataElements;

    public function __construct($dataElements)
    {
        $this->dataElements = $dataElements;
    }

    public function segmentId() {
        return $this->dataElements[0];
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {

        // Try to get a data element, ie: check for something like "ISA06"
        if (preg_match('/' . $this->segmentId() . '(\d+)/', $name, $matches)) {
            $index = intval(ltrim($matches[1], '0'));
            if ($index < count($this->dataElements)) {
                return $this->dataElements[$index];
            }
        }

        // Return the built in getter
        throw new Exception('Trying to access ' . $name . ' when no property for that name exists.');
    }

    // TODO: finish implementing

}