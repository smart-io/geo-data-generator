<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Region;

use SmartData\SmartDataGenerator\DataGenerator\Region\WikipediaRegion\WikipediaRegionParser;
use SmartData\SmartDataGenerator\DataGenerator\Region\WikipediaRegionList\WikipediaRegionList;

class RegionDataGenerator
{
    public function genereteAllRegion()
    {
        $regions = [];
        $regionList = (new WikipediaRegionList())->createWikipediaRegionList();
        $wikipediaRegionParser = new WikipediaRegionParser();
        foreach ($regionList as $region) {
            $regions[] = $wikipediaRegionParser->parseRegion($region);
        }
        return $regions;
    }
}
