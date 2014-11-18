<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Country\WikipediaCountryList;

use GuzzleHttp\Client;
use SmartData\SmartDataGenerator\Container;
use SmartData\SmartDataGenerator\HtmlParser;
use DOMElement;
use Symfony\Component\Console\Output\OutputInterface;

class WikipediaCountryList
{
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function createWikipediaCountryList()
    {
        $listParser = new WikipediaCountryListParser($this->container);
        $countryList = $listParser->parseCountryList();
        return $countryList;
    }
}
