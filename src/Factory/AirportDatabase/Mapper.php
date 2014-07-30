<?php
namespace SmartData\Factory\AirportDatabase;

use SmartData\Factory\Registry;

class Mapper
{
    const XML_FILENAME = 'airports.xml';
    const JSON_FILENAME = 'airports/airports.json';

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return array
     */
    public function mapXmlToJson()
    {
        $xmlFile = $this->registry->getConfig()->getStorage() . '/' . self::XML_FILENAME;
        $jsonFile =  $this->registry->getConfig()->getFactoryStorage() . '/' . self::JSON_FILENAME;

        $xml = $this->readXmlFile($xmlFile);

        $json = [];
        foreach ($xml as $xmlObject) {
            $json[] = [
                'name' => $xmlObject['name'],
                'city' => $xmlObject['city'],
                'countryName' => $xmlObject['country_name'],
                'countryCode' => $xmlObject['country_code'],
                'code' => $xmlObject['code'],
                'cityCode' => $xmlObject['city_code'],
                'latitude' => $xmlObject['latitude'],
                'longitude' => $xmlObject['longitude'],
                'altitude' => $xmlObject['altitude'],
                'timezone' => $xmlObject['timezone'],
                'dst' => $xmlObject['DST'],
                'isCity' => $xmlObject['is_city'],
                'isMajorAirport' => $xmlObject['is_major_airport'],
                'popularityTier' => $xmlObject['popularity_tier'],
            ];
        }

        $dir = dirname($jsonFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($jsonFile, json_encode($json));
        return $json;
    }

    /**
     * @param string $file
     * @return array
     */
    private function readXmlFile($file)
    {
        $content = file_get_contents($file);
        preg_match_all('{<row.+</row>}msU', $content, $matches);

        $content = "<?xml version='1.0'?>\n<rows>\n";
        foreach ($matches as $rows) {
            foreach ($rows as $row) {
                $content .= $row . "\n";
            }
            break;
        }
        $content .= "</rows>\n";

        $array = [];
        $xml = @simplexml_load_string($content);
        $count = 0;
        foreach ($xml->row as $row) {
            $array[$count] = [];
            foreach ($row->field as $field) {
                $attributes = current($field->attributes());
                $array[$count][$attributes['name']] = (string)$field;
            }
            $count++;
        }

        return $array;
    }
}
