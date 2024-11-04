<?php

namespace Uhin\X12Parser\EDI\Segments;

use Exception;
use JsonSerializable;

class Segment implements JsonSerializable
{

    /** @var array */
    protected $dataElements;

    public function __construct($dataElements)
    {
        $this->dataElements = $dataElements;
    }

    public function getSegmentId()
    {
        return $this->dataElements[0];
    }

    public function getDataElements()
    {
        return $this->dataElements;
    }

    /**
     * Magic method for detecting if elements of this segment are set.
     * Example:
     *   $gs = new GS(...);
     *   $gs02 = isset($gs->GS02) ?? '';
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        // Try to get a data element, ie: check for something like "GS02"
        if (preg_match('/' . $this->getSegmentId() . '(\d+)/', $name, $matches)) {
            $index = intval(ltrim($matches[1], '0'));
            if ($index < count($this->dataElements)) {
                return isset($this->dataElements[$index]);
            }
        }

        // Not Found
        return false;
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
        if (preg_match('/' . $this->getSegmentId() . '(\d+)/', $name, $matches)) {
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
        if (preg_match('/' . $this->getSegmentId() . '(\d+)/', $name, $matches)) {
            $index = intval(ltrim($matches[1], '0'));
            if ($index < count($this->dataElements)) {
                $this->dataElements[$index] = $value;
                return;
            } else {
                $this->dataElements = array_pad($this->dataElements, $index + 1, '');
                $this->dataElements[$index] = $value;
                return;
            }
        }

        // Something went wrong
        throw new Exception('Trying to access ' . $name . ' when no property for that name exists.');
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): mixed
    {
        $segmentId = $this->getSegmentId();

        $serialized = [
            $segmentId . "00" => $this->getSegmentId(),
        ];

        // Convert the data elements into an associative array of values, ie: {"GS01" => "...", "GS02" => "...", etc.}
        for ($element = 1; $element < count($this->dataElements); $element++) {
            $elementName = $segmentId . sprintf("%02d", $element);
            $serialized[$elementName] = $this->dataElements[$element];
        }

        return $serialized;
    }

    /**
     * Removes all empty data elements from the given segment.
     */
    public function trimEmptyDataElements()
    {
        while ("" === end($this->dataElements)) {
            array_pop($this->dataElements);
        }
    }

}