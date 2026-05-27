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

    public function test_count_st_segments()
    {
        $filePath = __DIR__ . '/test-files/837.edi';
        $this->assertFileExists($filePath);
        $fileContents = file_get_contents($filePath);
        $fileContents = str_replace(["\n", "\t", "\r"], '', $fileContents);
        $parser = new X12Parser($fileContents);
        $x12 = $parser->parse();

        foreach ($x12->ISA as $isa) {
            foreach ($isa->GS as $gs) {
                foreach ($gs->ST as $st) {
                    $result = $this->utility->countStSegments($st);
                    $this->assertEquals(24, $result);
                }
            }
        }
    }

    public function test_count_hl_segments()
    {
        $filePath = __DIR__ . ('/test-files/837.edi');
        $this->assertFileExists($filePath);
        $fileContents = file_get_contents($filePath);
        $fileContents = str_replace(["\n", "\t", "\r"], '', $fileContents);
        $parser = new X12Parser($fileContents);
        $x12 = $parser->parse();

        foreach ($x12->ISA as $isa) {
            foreach ($isa->GS as $gs) {
                foreach ($gs->ST as $st) {
                    foreach ($st->HL as $hl) {
                        $result = $this->utility->countHLSegments($hl);
                        $this->assertEquals(18, $result);
                    }
                }
            }
        }
    }

    public function test_repetition_separator_ignored()
    {
        $filePath = __DIR__ . ('/test-files/850_v4010.edi');
        $this->assertFileExists($filePath);
        $parser = new X12Parser(file_get_contents($filePath));
        $x12 = $parser->parse();

        $interchangeControlHeader = reset($x12->ISA);
        $functionalGroupHeader = reset($interchangeControlHeader->GS);
        $transactionSetHeader = reset($functionalGroupHeader->ST);

        $perSegmentValue = null;

        foreach ( $transactionSetHeader->properties as $segment ) {
            if ($segment->getSegmentId() === 'PER') {
                $perSegmentValue = $segment->PER02;
                break;
            }
        }

        $this->assertEquals('GRAINGER BUYER NAME', $perSegmentValue, 'The ISA11 was treated as a repetition separator.');
    }

    public function test_separators()
    {
        $filePath = __DIR__ . ('/test-files/850_v5010.edi');
        $this->assertFileExists($filePath);
        $parser = new X12Parser(file_get_contents($filePath));
        $x12 = $parser->parse();

        $interchangeControlHeader = reset($x12->ISA);
        $functionalGroupHeader = reset($interchangeControlHeader->GS);
        $transactionSetHeader = reset($functionalGroupHeader->ST);

        $refSegmentValue = null;
        $mea1SegmentValue = null;
        $mea2SegmentValue = null;

        foreach ($transactionSetHeader->properties as $segment) {
            if ($segment->getSegmentId() === 'REF') {
                $refSegmentValue = $segment->REF02;
            } elseif ($segment->getSegmentId() === 'MEA') {
                if ($segment->MEA02 === 'LN') {
                    $mea1SegmentValue = $segment->MEA04;
                } elseif ($segment->MEA02 === 'WT') {
                    $mea2SegmentValue = $segment->MEA04;
                }
            }
        }

        $this->assertSame(
            ['COLOR-RED', 'COLOR-BLUE', 'COLOR-BLACK'],
            $refSegmentValue,
            'The Repetition Separator-only value was\'t parsed correctly.'
        );

        $this->assertSame(
            [['IN', '2']],
            $mea1SegmentValue,
            'The Component Element Separator-only value was\'t parsed correctly.'
        );

        $this->assertSame(
            [['LB', '1'], ['KG', '1']],
            $mea2SegmentValue,
            'The Repetition Separator & Component Element Separator combined value was\'t parsed correctly.'
        );
    }
}
