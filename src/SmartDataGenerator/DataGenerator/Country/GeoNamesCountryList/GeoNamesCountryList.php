<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Country\GeoNamesCountryList;

use SmartData\SmartDataGenerator\Container;

class GeoNamesCountryList
{
    /**
     * @var Container
     */
    private $container;

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
    public function fetchGeoNamesCountryList()
    {
        return $this->container->getProvider()->getGeoNamesProvider()->getCountryInfo();
    }
}
