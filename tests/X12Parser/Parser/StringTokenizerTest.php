<?php

use PHPUnit\Framework\TestCase;
use Uhin\X12Parser\Parser\StringTokenizer;

class StringTokenizerTest extends TestCase
{
    /**
     * Assert getPosition returns the current byte offset in the stream after skipping whitespaces.
     *
     * @dataProvider stringProvider
     * 
     * @return void
     */
    public function testGetPosition(string $testString, int $setByteOffset, int $expectedByteOffset) : void
    {
        $tokenizer = new StringTokenizer($testString);
        $tokenizer->setPosition($setByteOffset);
        $this->assertEquals($expectedByteOffset, $tokenizer->getPosition());
    }

    public function stringProvider() : array
    {
        $testString = "something something darkside.";
        $testStringLength = strlen($testString);

        return [
            "pointer at end of string, expect string length returned." => [
                $testString,
                $testStringLength,
                $testStringLength
            ],
            "pointer at whitespace, expect byte position at non-whitespace returned" => [
                $testString,
                strpos($testString, ' '),
                strpos($testString, ' ') + 1
            ],
            "pointer at character, expect byte position of pointer" => [
                $testString,
                strpos($testString, 'g'),
                strpos($testString, 'g')
            ]
        ];
    }

}