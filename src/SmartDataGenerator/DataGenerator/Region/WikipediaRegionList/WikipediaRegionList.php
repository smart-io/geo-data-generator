<?php
namespace SmartData\Factory\RegionDatabase\WikipediaRegionList;

use SmartData\SmartData\Region\Type\FederalDistrict;
use SmartData\SmartData\Region\Type\Province;
use SmartData\SmartData\Region\Type\State;
use SmartData\SmartData\Region\Type\Territory;

class WikipediaRegionList
{
    /**
     * @return array
     */
    public function createWikipediaRegionList()
    {
        $listParser = new WikipediaRegionListParser();
        $regionList = $listParser->parseRegionList();

        $regions = [];
        $regionParser = new WikipediaRegionListItemParser();
        foreach ($regionList as $country => $regionTypes) {
            foreach ($regionTypes as $regionType => $regionItems) {
                foreach ($regionItems as $region) {
                    $region = $regionParser->parseRegion($region, $regionType, $country);
                    $region['country'] = strtoupper($country);
                    switch ($regionType) {
                        case 'states':
                            $region['type'] = State::class;
                            break;
                        case 'federal_districts':
                            $region['type'] = FederalDistrict::class;
                            break;
                        case 'territories':
                            $region['type'] = Territory::class;
                            break;
                        case 'provinces':
                            $region['type'] = Province::class;
                            break;
                    }
                    $regions[] = $region;
                }
            }
        }

        return $regions;
    }
}
