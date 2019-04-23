<?php

namespace Uhin\X12Parser\EDI\Traits;

trait HasProperties
{


    /** @var array */
    public $properties = [];


    /**
     * Removes a property segment at the given index and returns that
     * segment.
     *
     * @param $index integer The index to remove the segment
     * @return array The segment that was removed
     */
    public function removePropertyAtIndex($index)
    {
        return array_splice($this->properties, $index, 1);
    }

    /**
     * Loops on the properties of a segment and uses the callback to determine
     * if the property should be removed. The callback closure function should
     * return true if you want the property to be removed, and false if the property
     * should remain.
     *
     * @param $callback callback A callback function to determine if the property should be removed.
     */
    public function removeProperty($callback)
    {
        for ($i = 0; $i < count($this->properties); $i++) {
            if ($callback($this->properties[$i])) {
                $this->removePropertyAtIndex($i);
                $i--;
            }
        }
    }

}
