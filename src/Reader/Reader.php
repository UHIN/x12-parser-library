<?php

namespace Uhin\X12Parser\Reader;

interface Reader
{
    public function length() : int;
    public function reset() : void;
    public function seek(int $byteOffset) : void;
    public function offset() : int;
    public function consumed() : bool;
    public function slice(int $byteOffset, ?int $length = null) : ?string;
    public function at(int $byteOffset) : ?string;
    public function offsetOf(string $delimeter, ?int $startOffset = null);
}