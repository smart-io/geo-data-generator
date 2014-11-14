<?php
namespace SmartData\Factory\RegionDatabase;

use SmartData\Factory\RegionDatabase\WikiRegionList\WikiRegionList;

class RegionRepository
{
    public function fetchAll()
    {
        $regionList = $this->fetchWikiRegionList();
    }

    private function fetchWikiRegionList()
    {
        return (new WikiRegionList())->createWikiRegionList();
    }
}
