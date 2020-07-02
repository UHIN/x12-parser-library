<?php

use PHPUnit\Framework\TestCase;
use Uhin\X12Parser\Parser\StringTokenizer;

class StringTokenizerTest extends TestCase
{
    /**
     * Assert getPosition returns the current byte offset in the stream after skipping whitespaces.
     *
     * @dataProvider getPositionTestProvider
     * 
     * @return void
     */
    public function testGetPosition(string $testString, int $setByteOffset, int $expectedByteOffset) : void
    {
        $tokenizer = new StringTokenizer($testString);
        $tokenizer->setPosition($setByteOffset);
        $this->assertEquals($expectedByteOffset, $tokenizer->getPosition());
    }

    /**
     * Assert getSubstring returns a substring from the indicated offset of the indicated length, ignoring whitespace.
     *
     * @dataProvider getSubstringTestProvider
     * 
     * @return void
     */
    public function testGetSubstring(string $testString, int $startOffset, int $length, string $expectedSubstring) : void
    {
        $tokenizer = new StringTokenizer($testString);
        $substring = $tokenizer->getSubstring($startOffset, $length);
        $this->assertEquals($expectedSubstring, $substring);
    }

    /**
     * Data provider for testGetPosition
     *
     * @return array
     */
    public function getPositionTestProvider() : array
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

    /**
     * Data provider for 
     *
     * @return array
     */
    public function getSubstringTestProvider() : array
    {
        $testString = "something something darkside.";

        return [
            "Start position on character" => [
                $testString,
                10,
                9,
                "something"
            ],
            "Start position on space" => [
                $testString,
                9,
                9,
                "something"
            ],
            "Range includes space" => [
                $testString,
                8,
                10,
                "g somethin"
            ]
        ];
    }

}