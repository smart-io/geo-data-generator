<?php
namespace SmartData\SmartDataGenerator\RegionDatabase\WikipediaRegion;

class WikipediaRegionParser
{
    /**
     * @param array $region
     */
    public function parseRegion(array $region)
    {
        $names = (new WikipediaRegionNameParser)->parseRegion($region);
        var_dump($names);

        die();
    }
}
