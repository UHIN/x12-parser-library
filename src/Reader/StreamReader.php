<?php

namespace Uhin\X12Parser\Reader;

class StreamReader implements Reader
{
    /** @var string $stream */
    protected $stream;
    /** @var int $byteOffset */
    protected $byteOffset = 0;
    /** @var array $meta */
    protected $meta;
    /** @var int $length */
    protected $length;
    
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException("constructor requires a resource");
        }

        $this->meta = stream_get_meta_data($stream);

        if (!$this->meta['seekable']) {
            throw new \RuntimeException("Provided stream is not seekable.");
        }

        $this->stream = $stream;
    }

    public function length() : int
    {
        if (!$this->length) {
            $currentOffset = ftell($this->stream);
            fseek($this->stream, 0, SEEK_END);
            $this->length = ftell($this->stream);
            fseek($this->stream, $currentOffset);
        }

        return $this->length;
    }

    public function reset() : void
    {
        $this->seek(0);
    }

    public function seek(int $byteOffset) : void
    {
        fseek($this->stream, $byteOffset);
    }

    public function offset() : int
    {
        return ftell($this->stream);
    }

    public function consumed() : bool
    {
        return $this->byteOffset >= $this->length();
    }

    public function slice(int $byteOffset, ?int $length = null) : ?string
    {
        if (is_null($length)) {
            $length = $this->length() - $byteOffset;
        }

        $currentOffset = $this->offset();
        
        $this->seek($byteOffset);

        $read = "";
        $bytesRead = 0;

        while ($bytesRead < $length) {
            $character = fgetc($this->stream);

            // EOF
            if ($character === null) {
                break;
            }

            if ($this->shouldSkip($character)) {
                continue;
            }

            $read .= $character;

            $bytesRead++;
        }

        $this->seek($currentOffset);

        return $read ?: null;
    }

    public function offsetOf(string $needle, ?int $startOffset = null) : ?int
    {
        $currentOffset = $this->offset();

        if (is_null($startOffset)) {
            $startOffset = $currentOffset;

        }

        do {
            $position = ftell($this->stream);
            $character = fgetc($this->stream);

            // EOF
            if ($character === false) {
                return null;
            }
        } while ($character !== $needle);

        $this->seek($currentOffset);

        return $position;
    }

    public function at(int $byteOffset) : ?string
    {
        $currentOffset = $this->offset();
        $this->seek($byteOffset);
        $character = fgetc($this->stream);
        $this->seek($currentOffset);
        return $character ?: null;
    }

    protected function shouldSkip(string $character) : bool
    {
        return in_array($character, ["\n", "\r", "\t"]);
    }
}