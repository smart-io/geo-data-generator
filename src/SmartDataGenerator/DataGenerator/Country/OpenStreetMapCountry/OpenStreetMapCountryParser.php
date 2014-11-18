<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Country\OpenStreetMapCountry;

use GuzzleHttp\Exception\ClientException;
use SmartData\SmartDataGenerator\Container;
use SmartData\SmartDataGenerator\Provider\OpenStreetMap\OpenStreetMapParser;
use SmartData\SmartDataGenerator\Provider\OpenStreetMap\OpenStreetMapProvider;

class OpenStreetMapCountryParser
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
     * @param array $country
     * @return array
     */
    public function parseCountry(array $country)
    {
        $results = $this->openStreetMapProvider->searchAddress($country['countryName']);
        foreach ($results as $result) {
            if (
                isset($result['type']) && $result['type'] === 'administrative' &&
                (
                    isset($result['address']['country']) && isset($result['address']['country_code']) &&
                    count($result['address']) === 2
                ) ||
                (
                    isset($result['address']['country']) && isset($result['address']['country_code']) &&
                    isset($result['address']['continent']) &&count($result['address']) === 3
                )
            ) {
                $match = $result;
            }
        }
        if (!isset($match)) {
            return null;
        }

        try {
            $relation = $this->openStreetMapProvider->fetchRelation($match['osm_id']);
        } catch (ClientException $e) {
            return null;
        }

        $openStreetMapParser = new OpenStreetMapParser();

        $retval = [];
        $retval['timezone'] = $openStreetMapParser->parseTimeZone($relation);
        $retval['polygon'] = $openStreetMapParser->parsePolygon($match);
        $retval['latitude'] = $openStreetMapParser->parseLatitude($match);
        $retval['longitude'] = $openStreetMapParser->parseLongitude($match);

        return $retval;
    }
}
