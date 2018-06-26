<?php

namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class GS
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property-read string GS01 Functional ID code
 * @property-read string GS02 Application Sender's Code
 * @property-read string GS03 Application Receiver's Code
 * @property-read string GS04 Date
 * @property-read string GS05 Time
 * @property-read string GS06 Group Control Number
 * @property-read string GS07 Responsible Agency Code
 * @property-read string GS08 Version/Rel. Ind. ID Code
 */
class GS extends Envelope
{

    /** @var Segment */
    public $GE;

    /** @var array */
    public $ST = [];

}