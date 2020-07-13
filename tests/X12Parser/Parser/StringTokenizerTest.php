<?php

use PHPUnit\Framework\TestCase;
use Uhin\X12Parser\Reader\Reader;
use Uhin\X12Parser\Reader\StreamReader;
use Uhin\X12Parser\Reader\StringReader;
use Uhin\X12Parser\Parser\StringTokenizer;

class StringTokenizerTest extends TestCase
{
    const TEST_STRING = 'something something darkside.~was a terrible family guy episode.';

    /**
     * Assert getPosition returns the current byte offset in the stream after skipping whitespaces.
     *
     * @dataProvider streamReaderGetPositionTestProvider
     * @dataProvider stringReaderGetPositionTestProvider
     * 
     * @return void
     */
    public function testGetPosition(Reader $reader, int $setByteOffset, int $expectedByteOffset) : void
    {
        $tokenizer = new StringTokenizer($reader);
        $tokenizer->setPosition($setByteOffset);
        $this->assertEquals($expectedByteOffset, $tokenizer->getPosition());
    }

    /**
     * Assert getSubstring returns a substring from the indicated offset of the indicated length, ignoring whitespace.
     *
     * @dataProvider streamReaderGetSubstringTestProvider
     * @dataProvider stringReaderGetSubstringTestProvider
     * 
     * @return void
     */
    public function testGetSubstring(Reader $reader, int $startOffset, int $length, string $expectedSubstring) : void
    {
        $tokenizer = new StringTokenizer($reader);
        $substring = $tokenizer->getSubstring($startOffset, $length);
        $this->assertEquals($expectedSubstring, $substring);
    }

    /**
     * Assert next returns the next token in the stream.
     *
     * @dataProvider stringReaderNextTestProvider
     * @dataProvider streamReaderNextTestProvider
     * 
     * @param string $testString
     * @param integer $byteOffset
     * @param string $delimeter
     * @param string $expectedToken
     * @return void
     */
    public function testNext(Reader $reader, int $byteOffset, string $delimeter, string $expectedToken)
    {
        $tokenizer = new StringTokenizer($reader);
        $tokenizer->setPosition($byteOffset);
        $token = $tokenizer->next($delimeter);
        $this->assertEquals($expectedToken, $token);
    }

    public function stringReaderGetPositionTestProvider() : array
    {
        return $this->buildGetPositionProvider(new StringReader(static::TEST_STRING));
    }

    public function streamReaderGetPositionTestProvider() : array
    {
        return $this->buildGetPositionProvider($this->buildStreamReader(static::TEST_STRING));
    }

    public function stringReaderGetSubstringTestProvider() : array
    {
        return $this->buildGetSubstringProvider(new StringReader(static::TEST_STRING));
    }

    public function streamReaderGetSubstringTestProvider() : array
    {
        return $this->buildGetSubstringProvider($this->buildStreamReader(static::TEST_STRING));
    }

    public function stringReaderNextTestProvider() : array
    {
        return $this->buildNextTestProvider(new StringReader(static::TEST_STRING));
    }

    public function streamReaderNextTestProvider(): array
    {
        return $this->buildNextTestProvider($this->buildStreamReader(static::TEST_STRING));
    }

    protected function buildStreamReader(string $expectedString) : Reader
    {
        $resource = fopen("php://memory", "r+");
        fwrite($resource, $expectedString, strlen($expectedString));
        fseek($resource, 0);
        return new StreamReader($resource);
    }

    protected function buildGetPositionProvider(Reader $reader) : array
    {
        $length = strlen(static::TEST_STRING);
        $string = static::TEST_STRING;

        return [
            [
                $reader,
                $length,
                $length
            ],
            [
                $reader,
                strpos($string, ' '),
                strpos($string, ' ') + 1
            ],
            [
                $reader,
                strpos($string, 'g'),
                strpos($string, 'g')
            ]
        ];
    }

    protected function buildGetSubstringProvider(Reader $reader) : array
    {
        return [
            [
                $reader,
                10,
                9,
                "something"
            ],
            [
                $reader,
                9,
                9,
                "something"
            ],
            [
                $reader,
                8,
                10,
                "g somethin"
            ]
        ];
    }

    public function buildNextTestProvider(Reader $reader) : array
    {
        return [
            [
                $reader,
                0,
                '~',
                "something something darkside."
            ],
            [
                $reader, 
                strpos(static::TEST_STRING, '~') + 1,
                '~',
                "was a terrible family guy episode."
            ]
        ];
    }
}