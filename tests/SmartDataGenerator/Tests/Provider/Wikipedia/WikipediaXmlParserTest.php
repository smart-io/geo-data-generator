<?php
namespace SmartData\SmartDataGenerator\Tests\Provider\Wikipedia;

use PHPUnit_Framework_TestCase;
use SmartData\SmartDataGenerator\Provider\Wikipedia\WikipediaXmlParser;

class WikipediaXmlParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return string
     */
    public function getSearchResult()
    {
        return [
            [
                'xml' => file_get_contents(__DIR__ . "/../../_files/Wikipedia/Response/search.xml")
            ]
        ];
    }

    /**
     * @dataProvider getSearchResult
     * @param string $xml
     */
    public function testConvert($xml)
    {
        $result = (new WikipediaXmlParser)->parseXml($xml);
        $this->assertCount(10, $result->api[0]->query[0]->search[0]->p);
    }
}
