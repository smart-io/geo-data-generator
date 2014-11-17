<?php
namespace SmartData\SmartDataGenerator\Provider\OpenStreetMap;

use GuzzleHttp\Client;

class OpenStreetMapHttp implements OpenStreetMapHttpInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var OpenStreetMapCache
     */
    private $cache;

    public function __construct()
    {
        $this->client = new Client();
        $this->cache = new OpenStreetMapCache();
    }

    /**
     * @param string $file
     * @return string
     */
    public function get($file)
    {
        if ($this->cache->get($file)) {
            $response = $this->cache->get($file);
        } else {
            $response = $this->client->get($file)->getBody()->getContents();
            $this->cache->set($file, $response);
        }
        return $response;
    }
}
