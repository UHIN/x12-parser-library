<?php

namespace Uhin\X12Parser\Reader;

class StringReader implements Reader
{
    /** @var string $stream */
    protected $stream;
    /** @var int $byteOffset */
    protected $byteOffset = 0;
    /** @var int $length */
    protected $length;
    
    public function __construct(string $string)
    {
        $this->stream = trim(str_replace(["\n", "\t", "\r"], '', $string));
        $this->length = strlen($this->stream);
    }

    public function length() : int
    {
        return $this->length;
    }

    public function reset() : void
    {
        $this->seek(0);
    }

    public function seek(int $byteOffset) : void
    {
        $this->byteOffset = $byteOffset;
    }

    public function offset() : int
    {
        return $this->byteOffset;
    }

    public function consumed() : bool
    {
        return $this->byteOffset >= $this->length();
    }

    public function slice(int $byteOffset, ?int $length = null) : ?string
    {
        if (is_null($length)) {
            return substr($this->stream, $byteOffset) ?: null;
        }

        return substr($this->stream, $byteOffset, $length) ?: null;
    }

    public function offsetOf(string $needle, ?int $startOffset = null) : ?int
    {
        if (is_null($startOffset)) {
            $startOffset = $this->offset();
        }

        return strpos($this->stream, $needle, $startOffset) ?: null;
    }

    public function at(int $byteOffset) : ?string
    {
        return $this->stream[$byteOffset] ?? null;
    }
}