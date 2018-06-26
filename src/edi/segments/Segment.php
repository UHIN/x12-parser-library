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

    public function getDataElements() {
        return $this->dataElements;
    }

    /**
     * Magic method for accessing elements of this segment, such as "GS02".
     * Example:
     *   $gs = new GS(...);
     *   $gs02 = $gs->GS02;
     *
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        // Try to get a data element, ie: check for something like "GS02"
        if (preg_match('/' . $this->segmentId() . '(\d+)/', $name, $matches)) {
            $index = intval(ltrim($matches[1], '0'));
            if ($index < count($this->dataElements)) {
                return $this->dataElements[$index];
            }
        }

        // Something went wrong
        throw new Exception('Trying to access ' . $name . ' when no property for that name exists.');
    }

    /**
     * Magic method for setting elements of this segment, such as "GS02"
     * Example:
     *   $gs = new GS(...);
     *   $gs->GS02 = 'HT000015-001';
     *
     * @param string $name
     * @param $value
     * @throws Exception
     */
    public function __set($name, $value)
    {
        // Try to get a data element, ie: check for something like "GS02"
        if (preg_match('/' . $this->segmentId() . '(\d+)/', $name, $matches)) {
            $index = intval(ltrim($matches[1], '0'));
            if ($index < count($this->dataElements)) {
                $this->dataElements[$index] = $value;
                return;
            }
        }

        // Something went wrong
        throw new Exception('Trying to access ' . $name . ' when no property for that name exists.');
    }

}