<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Region\WikipediaRegion;

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
