<?php
namespace SmartData\SmartDataGenerator\RegionDatabase;

use SmartData\SmartDataGenerator\RegionDatabase\WikipediaRegion\WikipediaRegionParser;
use SmartData\SmartDataGenerator\RegionDatabase\WikipediaRegionList\WikipediaRegionList;

class RegionRepository
{
    public function fetchAll()
    {
        $regions = [];
        $regionList = (new WikipediaRegionList())->createWikipediaRegionList();
        $wikipediaRegionParser = new WikipediaRegionParser();
        foreach ($regionList as $region) {
            $wikipediaRegionParser->parseRegion($region);
        }
    }
}
