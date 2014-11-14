<?php
namespace SmartData\Factory\RegionDatabase\WikiRegionList;

use SmartData\SmartData\Region\Type\FederalDistrict;
use SmartData\SmartData\Region\Type\Province;
use SmartData\SmartData\Region\Type\State;
use SmartData\SmartData\Region\Type\Territory;

class WikiRegionList
{
    public function createWikiRegionList()
    {
        $listParser = new WikiRegionListParser();
        $regionList = $listParser->parseRegionList();

        $regions = [];
        $regionParser = new WikiRegionListItemParser();
        foreach ($regionList as $country => $regionTypes) {
            foreach ($regionTypes as $regionType => $regions) {
                foreach ($regions as $region) {
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
