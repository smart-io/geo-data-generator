<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Region;

use SmartData\SmartDataGenerator\Container;
use SmartData\SmartDataGenerator\DataGenerator\Region\WikipediaRegion\WikipediaRegionParser;
use SmartData\SmartDataGenerator\DataGenerator\Region\WikipediaRegionList\WikipediaRegionList;

class RegionDataGenerator
{
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function genereteAllRegion()
    {
        $regions = [];
        $regionList = (new WikipediaRegionList())->createWikipediaRegionList();
        $wikipediaRegionParser = new WikipediaRegionParser($this->container);
        foreach ($regionList as $region) {
            $regions[] = $wikipediaRegionParser->parseRegion($region);
        }
        return $regions;
    }
}
