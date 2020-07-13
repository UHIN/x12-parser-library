<?php

namespace Uhin\X12Parser\Parser;

use Psr\Log\LoggerInterface;
use Uhin\X12Parser\Reader\Reader;

class StringTokenizer
{
    /** @var Reader $reader */
    protected $reader;
    /** @var LoggerInterfacer $logger */
    protected $logger;

    public function __construct(
        Reader $reader,
        LoggerInterface $logger
    )
    {
        $this->reader = $reader;
        $this->logger = $logger;
        $this->reset();
    }

    public function reset()
    {
        $this->reader->reset();
    }

    public function getPosition()
    {
        if ($this->reader->consumed()) {
            return $this->reader->offset();
        }

        // skip over the whitespace
        $position = $this->reader->offset();
        $length = $this->reader->length();
        while ($position < ($length - 1) && ctype_space($this->reader->at($position))) {
            $position++;
        }

        return $position;
    }

    public function getStreamSize()
    {
        return $this->reader->length();
    }

    public function getSubstring($start, $length)
    {
        // skip over the whitespace
        $size = $this->reader->length();
        while ($start < ($size - 1) && ctype_space($this->reader->at($start))) {
            $start++;
        }
        
        return $this->reader->slice($start, $length);
    }

    public function setPosition($position)
    {
        $this->reader->seek($position);
    }

    public function isDone()
    {
        return $this->reader->consumed();
    }

    public function getCompletionPercent()
    {
        if ($this->reader->length() <= 0) {
            return 0;
        }
        
        return min($this->reader->offset(), $this->reader->length()) / $this->reader->length();
    }

    // Using PHP's strpos instead of a while loop (seems to be faster)
    public function next($delimiter, $incrementIndex = true)
    {
        // Check if we're done reading the file
        if ($this->isDone()) {
            return false;
        }

        $start = $this->reader->offset();
        $length = $this->reader->length();

        // skip over the whitespace
        while ($start < ($length - 1) && ctype_space($this->reader->at($start))) {
            $start++;
        }

        // Look for the next delimiter in the file
        $nextDelimiterIndex = $this->reader->offsetOf($delimiter);

        if ($nextDelimiterIndex === null) {
            // If no more delimiters can be found, then just return the rest of the file
            if ($incrementIndex) {
                $this->reader->seek($length);
            }
            
            return $this->reader->slice($start);
        } else {
            // If we found another delimiter, then return the text from the current position
            // up until the position of the next delimiter
            if ($incrementIndex) {
                $this->reader->seek($nextDelimiterIndex + 1);
            }

            return $this->reader->slice($start, ($nextDelimiterIndex - $start));
        }
    }

}