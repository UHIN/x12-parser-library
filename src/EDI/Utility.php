<?php

namespace Uhin\X12Parser\EDI;

use Carbon\Carbon;
use Uhin\X12Parser\EDI\Segments\HL;
use Uhin\X12Parser\EDI\Segments\ST;

class Utility
{
    /**
     * Will return the non-decimal version of the amount provided if it is not
     *   necessary.
     *
     *  0.00 => 0
     *  5.40 => 5.4
     *  20.00 => 20
     *  0020.00 => 20
     */
    public static function generateX12MonetaryValue(string $amount)
    {
        $amount = str_replace(',', '', $amount);
        if (ltrim($amount, '-0.') === '') {
            return '0';
        }

        $isNegative = str_starts_with($amount, '-');
        
        $amount = ltrim($amount, '-0');

        if (str_contains($amount, '.')) {
            $amount = rtrim($amount, '0');
            $amount = rtrim($amount, '.');
        }

        return ($isNegative ? '-' : '') . $amount;
    }

    /**
     * Translates from our date format standard to the X12 Standard
     *
     * @return string
     */
    public static function generateDTPFormatQualifier($format)
    {
        switch ($format) {
            case 'time':
                return 'TM';
            case 'date':
                return 'D8';
            case 'datetime':
                return 'DT';
            case 'range_date':
                return 'RD8';
            case 'range_datetime':
                return 'RDT';
            default:
                return $format;
        }
    }

    /**
     * Parses a date in the specified format
     *
     * @param  $datetime
     * @param  $format
     * @return string
     */
    public static function generateX12Time($date)
    {
        switch ($date->format) {
            case 'time':
                return Carbon::parse($date->time)->format('Hi');
            case 'date':
                return Carbon::parse($date->date)->format('Ymd');
            case 'datetime':
                return Carbon::parse($date->date.$date->time)->format('YmdHi');
            case 'range_date':
                return Carbon::parse($date->start_date)->format('Ymd')
                    .'-'
                    .Carbon::parse($date->end_date)->format('Ymd');
            case 'range_datetime':
                return Carbon::parse($date->start_date.$date->start_time)->format('YmdHi')
                    .'-'
                    .Carbon::parse($date->end_date.$date->end_time)->format('YmdHi');
            default:
                return $date;
        }
    }

    /**
     * @param ST $st
     * @return integer
     */
    public static function countStSegments($st)
    {
        // 1 for ST segment, 1 for SE segment
        $count = 2;

        // Count the non-HL properties inside of this ST segment
        $count += count($st->properties);

        // Count the HL segments inside of this ST segment
        if (isset($st->HL)) {
            foreach ($st->HL as $hl) {
                $count += self::countHlSegments($hl);
            }
        }

        return $count;
    }

    /**
     * @param HL $hl
     * @return integer
     */
    public static function countHlSegments($hl)
    {
        // 1 for this actual HL segment
        $count = 1;

        // Count the child HL segments
        foreach ($hl->HL as $childHL) {
            $count += self::countHlSegments($childHL);
        }

        // Count the non-HL child segments
        $count += count($hl->properties);

        return $count;
    }
}