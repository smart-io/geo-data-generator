<?php
namespace SmartData\SmartDataGenerator\Provider\Wikipedia;

use GuzzleHttp\Client;

class WikipediaHttp implements WikipediaHttpInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var WikipediaCache
     */
    private $cache;

    public function __construct()
    {
        $this->client = new Client();
        $this->cache = new WikipediaCache();
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
