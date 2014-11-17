<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Country\WikipediaCountryList;

use GuzzleHttp\Client;
use SmartData\SmartDataGenerator\HtmlParser;
use DOMElement;
use Symfony\Component\Console\Output\OutputInterface;

class WikipediaCountryList
{
    /**
     * @return array
     */
    public function createWikipediaCountryList()
    {
        $listParser = new WikipediaCountryListParser();
        $countryList = $listParser->parseCountryList();
        return $countryList;
    }
}
