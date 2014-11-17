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
        $countries = [];
        $countryList = (new WikipediaCountryList())->createWikipediaCountryList();

        $wikipediaCountryParser = new WikipediaCountryParser();
        foreach ($countryList as $item) {
            if ($country = $wikipediaCountryParser->parseCountry($item)) {
                $countries[] = array_merge($item, $country);
            }
        }
        
        return $countries;
    }
}
