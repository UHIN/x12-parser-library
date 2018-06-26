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

    public function getStringLength() {
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

    public function getCompletionPercent() {
        if ($this->stringLength <= 0) {
            return 0;
        }
        return min($this->currentIndex, $this->stringLength) / $this->stringLength;
    }

    public function next($delimiter, $incrementIndex = true)
    {
        if ($this->isDone()) {
            return false;
        }
        $start = $this->currentIndex;
        $end = $start;
        $found = false;
        while ($end < $this->stringLength - 1) {
            $end++;
            if ($this->string[$end] === $delimiter) {
                $found = true;
                break;
            }
        }
        if ($found) {
            if ($incrementIndex) {
                $this->currentIndex = $end + 1;
            }
            return substr($this->string, $start, ($end - $start));
        } else {
            if ($incrementIndex) {
                $this->currentIndex = $this->stringLength;
            }
            return substr($this->string, $start);
        }
    }

}
