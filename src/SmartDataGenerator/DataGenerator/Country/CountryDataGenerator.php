<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Country;

use SmartData\SmartDataGenerator\Container;
use SmartData\SmartDataGenerator\DataGenerator\Country\GeoNamesCountry\GeoNamesCountryParser;
use SmartData\SmartDataGenerator\DataGenerator\Country\GeoNamesCountryList\GeoNamesCountryList;
use SmartData\SmartDataGenerator\DataGenerator\Country\OpenStreetMapCountry\OpenStreetMapCountryParser;

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
        $countryList = (new GeoNamesCountryList($this->container))->fetchGeoNamesCountryList();

        $geoNamesCountryParser = new GeoNamesCountryParser($this->container);
        foreach ($countryList as $key => $item) {
            if ($country = $geoNamesCountryParser->parseCountry($item)) {
                $countryList[$key] = array_merge((array)$item, $country);
            }
        }

        $openStreetMapCountryParser = new OpenStreetMapCountryParser($this->container);
        foreach ($countryList as $key => $item) {
            if ($country = $openStreetMapCountryParser->parseCountry($item)) {
                $countryList[$key] = array_merge((array)$item, $country);
            }
        }

        return $countryList;
    }
}
