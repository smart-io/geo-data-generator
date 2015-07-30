<?php

namespace Smart\Geo\Generator\DataGenerator\Region\OpenStreetMapRegion;

use Smart\Geo\Generator\Container;
use Smart\Geo\Generator\Provider\OpenStreetMap\OpenStreetMapParser;
use Smart\Geo\Generator\Provider\OpenStreetMap\OpenStreetMapProvider;
use GuzzleHttp\Exception\ClientException;

class OpenStreetMapRegionParser
{
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->openStreetMapProvider = new OpenStreetMapProvider($container);
    }

    /**
     * @param array $region
     * @return array
     */
    public function parseRegion(array $region)
    {
        $retval = [];
        $results = $this->openStreetMapProvider->searchAddress("{$region['name']}, {$region['country']}");
        foreach ($results as $search) {
            if (isset($search['type']) && $search['type'] === 'administrative') {
                $match = $search;
                break;
            }
        }
        if (!isset($match)) {
            trigger_error('Unable to get search information on ' . $region['name'], E_USER_ERROR);
            return null;
        }

        try {
            $relation = $this->openStreetMapProvider->fetchRelation($match['osm_id']);
        } catch (ClientException $e) {
            trigger_error('Unable to get relation information on ' . $region['name'], E_USER_ERROR);
            return null;
        }

        $openStreetMapParser = new OpenStreetMapParser();

        $retval['names'] = $openStreetMapParser->parseNames($relation);
        $retval['timezone'] = $openStreetMapParser->parseTimeZone($relation);
        //$retval['polygon'] = $this->parsePolygon($search);
        $retval['bounding_box'] = $openStreetMapParser->parseBoundingBox($match);
        $retval['latitude'] = $openStreetMapParser->parseLatitude($match);
        $retval['longitude'] = $openStreetMapParser->parseLongitude($match);
        $retval['long_code'] = $openStreetMapParser->parseRegionLongCode($relation);

        return $retval;
    }
}
