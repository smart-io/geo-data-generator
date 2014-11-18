<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Country;

use SmartData\SmartDataGenerator\Container;
use SmartData\SmartDataGenerator\DataGenerator\Country\OpenStreetMapCountry\OpenStreetMapCountryParser;
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
        $countryList = (new WikipediaCountryList($this->container))->createWikipediaCountryList();

        $wikipediaCountryParser = new WikipediaCountryParser($this->container);
        foreach ($countryList as $key => $item) {
            if ($country = $wikipediaCountryParser->parseCountry($item)) {
                $countryList[$key] = array_merge($item, $country);
            }
        }

        $openStreetMapCountryParser = new OpenStreetMapCountryParser($this->container);
        foreach ($countryList as $key => $item) {
            if ($country = $openStreetMapCountryParser->parseCountry($item)) {
                $countries[] = array_merge($item, $country);
            }
        }

        return $countries;
    }
}
