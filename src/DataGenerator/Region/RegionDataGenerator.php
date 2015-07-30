<?php

namespace Smart\Geo\Generator\DataGenerator\Region;

use Smart\Geo\Generator\Container;
use Smart\Geo\Generator\DataGenerator\Region\OpenStreetMapRegion\OpenStreetMapRegionParser;
use Smart\Geo\Generator\DataGenerator\Region\WikipediaRegion\WikipediaRegionParser;
use Smart\Geo\Generator\DataGenerator\Region\WikipediaRegionList\WikipediaRegionList;

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
        $regionList = (new WikipediaRegionList($this->container))->createWikipediaRegionList();
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
