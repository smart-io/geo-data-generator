<?php
namespace SmartData\Factory\RegionDatabase;

use SmartData\Factory\RegionDatabase\WikipediaRegionList\WikipediaRegionList;

class RegionRepository
{
    public function fetchAll()
    {
        $regionList = $this->fetchWikipediaRegionList();
        var_dump($regionList);
    }

    private function fetchWikipediaRegionList()
    {
        return (new WikipediaRegionList())->createWikipediaRegionList();
    }
}
