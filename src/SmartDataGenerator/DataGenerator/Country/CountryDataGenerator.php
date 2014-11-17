<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Country;

use SmartData\SmartDataGenerator\Container;
use SmartData\SmartDataGenerator\DataGenerator\Country\WikipediaCountry\WikipediaCountryParser;
use SmartData\SmartDataGenerator\DataGenerator\Country\WikipediaCountryList\WikipediaCountryList;

class CountryDataGenerator
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
    public function genereteAllCountries()
    {
        $regions = [];
        $regionList = (new WikipediaCountryList())->createWikipediaCountryList();

        $wikipediaCountryParser = new WikipediaCountryParser();
        foreach ($regionList as $item) {
            $wikipediaCountryParser->parseCountry($item);
        }

        /*$openStreetMapRegionParser = new OpenStreetMapRegionParser($this->container);
        foreach ($regionList as $region) {
            $regions[] = array_merge(
                $region,
                $openStreetMapRegionParser->parseRegion($region)
            );
        }*/
        return $regions;
    }
}
