<?php
namespace SmartData\SmartDataGenerator\Provider\OpenStreetMap;

use SmartData\SmartDataGenerator\Provider\Wikipedia\WikipediaXmlParser;

class OpenStreetMapProvider
{
    const SEARCH_ADDRESS_URL = 'http://nominatim.openstreetmap.org/search?q=%s&format=json&polygon=0&addressdetails=1&limit=10&accept-language=%s';
    const RELATION_URL = 'http://www.openstreetmap.org/api/0.6/relation/%s';

    /**
     * @var array
     */
    private $overrideSearch = [
        'Washington, D.C.' => 'District of Columbia'
    ];

    public function __construct()
    {
        $this->http = new OpenStreetMapHttp();
    }

    /**
     * @param string $address
     * @param string $language
     * @return array
     */
    public function searchAddress($address, $language = 'en')
    {
        foreach ($this->overrideSearch as $match => $value) {
            $address = str_replace($match, $value, $address);
        }
        $url = sprintf(self::SEARCH_ADDRESS_URL, urlencode($address), $language);
        return json_decode($this->http->get($url), true);
    }

    /**
     * @param string $relationId
     * @param string $language
     * @return object
     */
    public function fetchRelation($relationId, $language = 'en')
    {
        $url = sprintf(self::RELATION_URL, urlencode($relationId), $language);
        return (new WikipediaXmlParser)->parseXml($this->http->get($url));
    }
}
