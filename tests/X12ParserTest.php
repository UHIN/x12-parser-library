<?php

use PHPUnit\Framework\TestCase;
use Uhin\X12Parser\Parser\X12Parser;
use Uhin\X12Parser\Reader\StreamReader;
use Uhin\X12Parser\Parser\StringTokenizer;
use Uhin\X12Parser\Serializer\X12Serializer;

final class X12ParserTest extends TestCase
{

    /**
     * @param $fileType
     * @throws Exception
     */
    private function runFileParserTest($fileType)
    {
        // Parse the file into memory
        $this->assertFileExists("./tests/test-files/{$fileType}.txt");
        $fileContents = file_get_contents("./tests/test-files/{$fileType}.txt");
        $stream = fopen("./tests/test-files/{$fileType}.txt", "r");
        $fileContents = str_replace(["\n", "\t", "\r"], '', $fileContents);

        $parser = new X12Parser(new StringTokenizer(new StreamReader($stream)));
        $x12 = $parser->parse();

        // Serialize the object back into X12
        $serializer = new X12Serializer($x12);
        $serialized = $serializer->serialize();
        $serialized = str_replace(["\n", "\t", "\r"], '', $serialized);

        $this->assertEquals(trim($fileContents), trim($serialized));
    }

    /**
     * @throws Exception
     */
    public function testTA1Parser(): void
    {
        $this->runFileParserTest('TA1');
    }

}