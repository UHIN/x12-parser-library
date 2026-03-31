<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Uhin\X12Parser\EDI\Utility;

class EDIUtilityTest extends TestCase
{
    public function test_generate_X12_monetary_value()
    {
        $utility = new Utility();

        $this->assertEquals($utility->generateX12MonetaryValue('0.00'), '0');
        $this->assertEquals($utility->generateX12MonetaryValue('00.00'), '0');
        $this->assertEquals($utility->generateX12MonetaryValue('0.0'), '0');
        $this->assertEquals($utility->generateX12MonetaryValue('.0'), '0');
        $this->assertEquals($utility->generateX12MonetaryValue('0.'), '0');
        $this->assertEquals($utility->generateX12MonetaryValue('000.000'), '0');
        $this->assertEquals($utility->generateX12MonetaryValue('1.23'), '1.23');
        $this->assertEquals($utility->generateX12MonetaryValue('-1.23'), '-1.23');
        $this->assertEquals($utility->generateX12MonetaryValue('1'), '1');
        $this->assertEquals($utility->generateX12MonetaryValue('-1'), '-1');
        $this->assertEquals($utility->generateX12MonetaryValue('0100'), '100');
        $this->assertEquals($utility->generateX12MonetaryValue('1.00'), '1');
        $this->assertEquals($utility->generateX12MonetaryValue('-1.00'), '-1');
        $this->assertEquals($utility->generateX12MonetaryValue('0.50'), '.5');
        $this->assertEquals($utility->generateX12MonetaryValue('-0.50'), '-.5');
        $this->assertEquals($utility->generateX12MonetaryValue('01.23'), '1.23');
        $this->assertEquals($utility->generateX12MonetaryValue('-01.23'), '-1.23');
    }
}
