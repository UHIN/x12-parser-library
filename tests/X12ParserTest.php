<?php

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use Monolog\Handler\NullHandler;
use Uhin\X12Parser\Reader\Reader;
use Monolog\Handler\StreamHandler;
use Uhin\X12Parser\Parser\X12Parser;
use Uhin\X12Parser\Reader\StreamReader;
use Uhin\X12Parser\Reader\StringReader;
use Uhin\X12Parser\Parser\StringTokenizer;
use Uhin\X12Parser\Serializer\X12Serializer;

final class X12ParserTest extends TestCase
{
    /**
     * 
     * @dataProvider streamReaderProvider
     * @dataProvider stringReaderProvider
     *
     * @param X12Parser $parser
     * @param string $expectedContents
     * @return void
     */
    public function testParser(X12Parser $parser, string $expectedContents)
    {
        $x12 = $parser->parse();
        $serializer = new X12Serializer($x12);
        $serialized = $serializer->serialize();
        $serialized = str_replace(["\n", "\t", "\r"], '', $serialized);
        $this->assertEquals($expectedContents, trim($serialized));
    }

    public function streamReaderProvider()
    {
        return [
            'StreamReader - TA1' => [
                $this->parserFor(
                    'TA1',
                    StreamReader::class
                ),
                $this->getFileContents('TA1')
            ]
        ];
    }

    public function stringReaderProvider()
    {
        return [
            'StringReader - TA1' => [
                $this->parserFor(
                    'TA1',
                    StringReader::class
                ),
                $this->getFileContents('TA1')
            ]
        ];
    }

    protected function parserFor(string $testFilename, string $readerClass) : X12Parser
    {
        $logger = $this->getLogger();

        switch ($readerClass) {
            case (StringReader::class):
                $reader = new $readerClass($this->getFileContents($testFilename));
            break;
            case (StreamReader::class):
                $reader = new $readerClass($this->getResource($testFilename));
            break;
        }
        
        return new X12Parser($this->tokenizerFor($reader, $logger), $logger);
    }

    protected function tokenizerFor(Reader $reader, LoggerInterface $logger) : StringTokenizer
    {
        return new Stringtokenizer($reader, $logger);
    }

    protected function getFileContents(string $testFilename) : string
    {
        $contents = file_get_contents($this->getTestFilePath($testFilename));
        $contents = str_replace(["\n", "\t", "\r"], '', $contents);
        return $contents;
    }

    protected function getTestFilePath(string $testFilename) : string
    {
        return "./tests/test-files/${testFilename}.txt";
    }

    protected function getResource(string $testFilename)
    {
        return fopen($this->getTestFilePath($testFilename), 'r');
    }

    protected function getLogger() : LoggerInterface
    {
        $logger = new Logger('phpunit');
        $logger->pushHandler(new NullHandler(Logger::DEBUG));
        return $logger;
    }
}