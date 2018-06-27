<?php

namespace Uhin\X12Parser\Parser;

class StringTokenizer
{

    private $string;
    private $stringLength;
    private $currentIndex;

    public function __construct($string)
    {
        $this->string = $string;
        $this->stringLength = strlen($this->string);
        $this->reset();
    }

    public function reset()
    {
        $this->setPosition(0);
    }

    public function getPosition()
    {
        return $this->currentIndex;
    }

    public function getStringLength()
    {
        return $this->stringLength;
    }

    public function getSubstring($start, $length)
    {
        return substr($this->string, $start, $length);
    }

    public function setPosition($position)
    {
        $this->currentIndex = $position;
    }

    public function isDone()
    {
        return $this->currentIndex >= $this->stringLength;
    }

    public function getCompletionPercent()
    {
        if ($this->stringLength <= 0) {
            return 0;
        }
        return min($this->currentIndex, $this->stringLength) / $this->stringLength;
    }

    // Using a while loop to find the next delimiter
    //public function next($delimiter, $incrementIndex = true)
    //{
    //    // Check if we're done reading the file
    //    if ($this->isDone()) {
    //        return false;
    //    }
    //
    //    $start = $this->currentIndex;
    //    $end = $start;
    //
    //    // Look for the next delimiter in the file
    //    $found = false;
    //    while ($end < $this->stringLength - 1) {
    //        $end++;
    //        if ($this->string[$end] === $delimiter) {
    //            $found = true;
    //            break;
    //        }
    //    }
    //
    //    if ($found) {
    //        // If we found another delimiter, then return the text from the current position
    //        // up until the position of the next delimiter
    //        if ($incrementIndex) {
    //            $this->currentIndex = $end + 1;
    //        }
    //        return substr($this->string, $start, ($end - $start));
    //
    //    } else {
    //        // If no more delimiters can be found, then just return the rest of the file
    //        if ($incrementIndex) {
    //            $this->currentIndex = $this->stringLength;
    //        }
    //        return substr($this->string, $start);
    //    }
    //}

    // Using PHP's strpos instead of a while loop (seems to be faster)
    public function next($delimiter, $incrementIndex = true)
    {
        // Check if we're done reading the file
        if ($this->isDone()) {
            return false;
        }

        $start = $this->currentIndex;

        // Look for the next delimiter in the file
        $nextDelimiterIndex = strpos($this->string, $delimiter, $this->currentIndex);

        if ($nextDelimiterIndex === false) {
            // If no more delimiters can be found, then just return the rest of the file
            if ($incrementIndex) {
                $this->currentIndex = $this->stringLength;
            }
            return substr($this->string, $start);

        } else {
            // If we found another delimiter, then return the text from the current position
            // up until the position of the next delimiter
            if ($incrementIndex) {
                $this->currentIndex = $nextDelimiterIndex + 1;
            }
            return substr($this->string, $start, ($nextDelimiterIndex - $start));
        }
    }

}