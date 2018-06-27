<?php

use PHPUnit\Framework\TestCase;
use Uhin\X12Parser\Parser\X12Parser;
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
        $fileContents = str_replace(["\n", "\t", "\r"], '', $fileContents);
        $parser = new X12Parser($fileContents);
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
    public function test277Parser(): void
    {
        $this->runFileParserTest('277');
    }

    /**
     * @throws Exception
     */
    public function test835Parser(): void
    {
        $this->runFileParserTest('835');
    }

    /**
     * @throws Exception
     */
    public function test837Parser(): void
    {
        $this->runFileParserTest('837');
    }

    /**
     * @throws Exception
     */
    public function test999Parser(): void
    {
        $this->runFileParserTest('999');
    }

    /**
     * @throws Exception
     */
    public function testTA1Parser(): void
    {
        $this->runFileParserTest('TA1');
    }

}