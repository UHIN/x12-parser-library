<?php

namespace Uhin\X12Parser\Serializer;

use Uhin\X12Parser\EDI\X12;

abstract class Serializer
{

    /** @var X12 */
    protected $x12;

    public function __construct(X12 &$x12)
    {
        $this->x12 = $x12;
    }

    abstract public function serialize();

}