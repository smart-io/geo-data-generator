<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Region;

use SmartData\SmartDataGenerator\Container;
use SmartData\SmartDataGenerator\DataGenerator\Region\OpenStreetMapRegion\OpenStreetMapRegionParser;
use SmartData\SmartDataGenerator\DataGenerator\Region\WikipediaRegion\WikipediaRegionParser;
use SmartData\SmartDataGenerator\DataGenerator\Region\WikipediaRegionList\WikipediaRegionList;

class RegionDataGenerator
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
    public function genereteAllRegion()
    {
        $regions = [];
        $regionList = (new WikipediaRegionList())->createWikipediaRegionList();
        $openStreetMapRegionParser = new OpenStreetMapRegionParser($this->container);
        foreach ($regionList as $region) {
            $regions[] = array_merge(
                $region,
                $openStreetMapRegionParser->parseRegion($region)
            );
        }
        return $regions;
    }
}
