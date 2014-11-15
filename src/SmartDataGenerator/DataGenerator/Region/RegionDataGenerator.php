<?php
namespace SmartData\Factory\RegionDatabase;

use SmartData\Factory\RegionDatabase\WikipediaRegion\WikipediaRegionParser;
use SmartData\Factory\RegionDatabase\WikipediaRegionList\WikipediaRegionList;

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
