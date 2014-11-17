<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Region\OpenStreetMapRegion;

use SmartData\SmartDataGenerator\Provider\OpenStreetMap\OpenStreetMapProvider;
use GuzzleHttp\Exception\ClientException;

class OpenStreetMapRegionParser
{
    public function __construct()
    {
        $this->openStreetMapProvider = new OpenStreetMapProvider();
    }

    /**
     * @param array $region
     * @return array
     */
    public function parseRegion(array $region)
    {
        $retval = [];
        $query = "{$region['name']}, {$region['country']}";
        $searchResults = $this->openStreetMapProvider->searchAddress($query);
        foreach ($searchResults as $searchResult) {
            if (isset($searchResult['type']) && $searchResult['type'] === 'administrative') {
                $search = $searchResult;
                break;
            }
        }
        if (!isset($search)) {
            trigger_error('Unable to get search information on ' . $region['name'], E_USER_ERROR);
            return null;
        }

        try {
            $relation = $this->openStreetMapProvider->fetchRelation($search['osm_id']);
        } catch (ClientException $e) {
            trigger_error('Unable to get relation information on ' . $region['name'], E_USER_ERROR);
            return null;
        }

        $retval['names'] = $this->parseNames($relation);
        $retval['timezone'] = $this->parseTimeZone($relation);
        //$retval['polygon'] = $this->parsePolygon($search);
        $retval['bounding_box'] = $this->parseBoundingBox($search);
        $retval['latitude'] = $this->parseLatitude($search);
        $retval['longitude'] = $this->parseLongitude($search);
        $retval['long_code'] = $this->parseLongCode($relation);

        return $retval;
    }

    /**
     * @param object $content
     * @return array
     */
    private function parseNames($content)
    {
        $names = [];
        foreach ($content->osm[0]->relation[0]->tag as $tag) {
            if (stripos($tag->attributes->k, 'name:') === 0) {
                $names[substr($tag->attributes->k, strlen('name:'))] = $tag->attributes->v;
            }
        }
        return $names;
    }

    /**
     * @param object $content
     * @return string
     */
    private function parseTimeZone($content)
    {
        foreach ($content->osm[0]->relation[0]->tag as $tag) {
            if ($tag->attributes->k === 'timezone') {
                return $tag->attributes->v;
            }
        }
        return null;
    }

    /**
     * @param array $content
     * @return array
     * @todo
     */
    private function parsePolygon($content)
    {
        $coordinates = [];
        if (isset($content['polygonpoints'])) {
            foreach ($content['polygonpoints'] as $coordinate) {
                $coordinates[] = [$coordinate[0], $coordinate[1]];
            }
        } else {
            trigger_error('Unable to get polygon points for ' . $content['display_name']);
        }
        return $coordinates;
    }

    /**
     * @param array $content
     * @return array
     */
    private function parseBoundingBox($content)
    {
        $boundingBox = [
            'nortWestCorner' => [
                'latitude' => $content['boundingbox'][1],
                'longitude' => $content['boundingbox'][2]
            ],
            'southEastCorner' => [
                'latitude' => $content['boundingbox'][0],
                'longitude' => $content['boundingbox'][3]
            ]
        ];
        return $boundingBox;
    }

    /**
     * @param array $content
     * @return string
     */
    private function parseLatitude($content)
    {
        return $content['lat'];
    }

    /**
     * @param array $content
     * @return string
     */
    private function parseLongitude($content)
    {
        return $content['lon'];
    }

    /**
     * @param object $content
     * @return string
     */
    private function parseLongCode($content)
    {
        foreach ($content->osm[0]->relation[0]->tag as $tag) {
            if ($tag->attributes->k === 'ISO3166-2') {
                return $tag->attributes->v;
            }
        }
        return null;
    }
}
