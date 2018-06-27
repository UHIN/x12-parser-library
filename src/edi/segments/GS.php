<?php

namespace Uhin\X12Parser\EDI\Segments;

/**
 * Class GS
 * @package Uhin\X12Parser\EDI\Segments
 *
 * @property string GS01 Functional ID code
 * @property string GS02 Application Sender's Code
 * @property string GS03 Application Receiver's Code
 * @property string GS04 Date
 * @property string GS05 Time
 * @property string GS06 Group Control Number
 * @property string GS07 Responsible Agency Code
 * @property string GS08 Version/Rel. Ind. ID Code
 */
class GS extends Segment
{

    /** @var Segment */
    public $GE;

    /** @var array */
    public $ST = [];

}