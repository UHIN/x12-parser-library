<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Uhin\X12Parser\EDI\Utility;
use Uhin\X12Parser\Parser\X12Parser;

class EDIUtilityTest extends TestCase
{
    private Utility $utility;

    protected function setUp() : void
    {
        $this->utility = new Utility();
    }

    public function test_generate_X12_monetary_value()
    {
        $this->assertEquals($this->utility->generateX12MonetaryValue('0.00'), '0');
        $this->assertEquals($this->utility->generateX12MonetaryValue('00.00'), '0');
        $this->assertEquals($this->utility->generateX12MonetaryValue('0.0'), '0');
        $this->assertEquals($this->utility->generateX12MonetaryValue('.0'), '0');
        $this->assertEquals($this->utility->generateX12MonetaryValue('0.'), '0');
        $this->assertEquals($this->utility->generateX12MonetaryValue('000.000'), '0');
        $this->assertEquals($this->utility->generateX12MonetaryValue('1.23'), '1.23');
        $this->assertEquals($this->utility->generateX12MonetaryValue('-1.23'), '-1.23');
        $this->assertEquals($this->utility->generateX12MonetaryValue('1'), '1');
        $this->assertEquals($this->utility->generateX12MonetaryValue('-1'), '-1');
        $this->assertEquals($this->utility->generateX12MonetaryValue('0100'), '100');
        $this->assertEquals($this->utility->generateX12MonetaryValue('1.00'), '1');
        $this->assertEquals($this->utility->generateX12MonetaryValue('-1.00'), '-1');
        $this->assertEquals($this->utility->generateX12MonetaryValue('0.50'), '.5');
        $this->assertEquals($this->utility->generateX12MonetaryValue('-0.50'), '-.5');
        $this->assertEquals($this->utility->generateX12MonetaryValue('01.23'), '1.23');
        $this->assertEquals($this->utility->generateX12MonetaryValue('-01.23'), '-1.23');
    }

    public function test_generate_dtp_format_qualifier_for_time_format()
    {
        $format = 'time';

        $result = $this->utility->generateDTPFormatQualifier($format);

        $this->assertEquals('TM', $result);
    }

    public function test_generate_dtp_format_qualifier_for_date_format()
    {
        $format = 'date';

        $result = $this->utility->generateDTPFormatQualifier($format);

        $this->assertEquals('D8', $result);
    }

    public function test_generate_dtp_format_qualifier_for_datetime_format()
    {
        $format = 'datetime';
        $result = $this->utility->generateDTPFormatQualifier($format);
        $this->assertEquals('DT', $result);
    }

    public function test_generate_dtp_format_qualifier_for_range_date_format()
    {
        $format = 'range_date';
        $result = $this->utility->generateDTPFormatQualifier($format);
        $this->assertEquals('RD8', $result);
    }

    public function test_generate_dtp_format_qualifier_for_range_datetime_format()
    {
        $format = 'range_datetime';
        $result = $this->utility->generateDTPFormatQualifier($format);
        $this->assertEquals('RDT', $result);
    }

    public function test_generate_dtp_format_qualifier_returns_original_value_for_unknown_format()
    {
        $format = 'unknown';
        $result = $this->utility->generateDTPFormatQualifier($format);
        $this->assertEquals('unknown', $result);
    }

    public function test_generate_x12_time_for_time_format()
    {
        $date = (object) [
            'format' => 'time',
            'time' => '1970-01-01 01:30:00'
        ];
        $result = $this->utility->generateX12Time($date);
        $this->assertEquals('0130', $result);
    }

    public function test_generate_x12_time_for_date_format()
    {
        $date = (object) [
            'format' => 'date',
            'date' => '1970-01-01'
        ];
        $result = $this->utility->generateX12Time($date);
        $this->assertEquals('19700101', $result);
    }

    public function test_generate_x12_time_for_datetime_format()
    {
        $date = (object) [
            'format' => 'datetime',
            'date' => '1970-01-01',
            'time' => '01:30:00'
        ];
        $result = $this->utility->generateX12Time($date);
        $this->assertEquals('197001010130', $result);
    }

    public function test_generate_x12_time_for_range_date_format()
    {
        $date = (object) [
            'format' => 'range_date',
            'start_date' => '1970-01-01',
            'end_date' => '2000-01-01'
        ];
        $result = $this->utility->generateX12Time($date);
        $this->assertEquals('19700101-20000101', $result);
    }

    public function test_generate_x12_time_for_range_datetime_format()
    {
        $date = (object) [
            'format' => 'range_datetime',
            'start_date' => '1970-01-01',
            'start_time' => '01:30:00',
            'end_date' => '2000-01-01',
            'end_time' => '01:30:00'
        ];
        $result = $this->utility->generateX12Time($date);
        $this->assertEquals('197001010130-200001010130', $result);
    }

    public function test_generate_x12_time_returns_original_value_for_unknown_format()
    {
        $date = (object) [
            'format' => 'unknown',
            'time' => '013000'
        ];
        $result = $this->utility->generateX12Time($date);
        $this->assertEquals($date, $result);
    }
}
