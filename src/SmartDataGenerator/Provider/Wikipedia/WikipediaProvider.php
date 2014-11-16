<?php
namespace SmartData\SmartDataGenerator\Provider\Wikipedia;

use GuzzleHttp\Client;
use SimpleXMLElement;

class WikipediaProvider
{
    const SEARCH_URL = 'http://en.wikipedia.org/w/api.php?action=query&list=search&srsearch=%s&srprop=&format=xml&continue';

    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->cache = new WikipediaCache();
    }

    /**
     * @param SimpleXMLElement $xml
     * @return object
     */
    private function convertSimpleXmlElementToObject(SimpleXMLElement $xml)
    {
        $object = (object)[];
        /** @var SimpleXMLElement $value */
        foreach ($xml as $key => $value) {
            if ($value->count()) {
                $property = (object)[];
                foreach ($value as $itemKey => $item) {
                    if (!isset($property->{$itemKey})) {
                        $property->{$itemKey} = [];
                    }
                    if ($item instanceof SimpleXMLElement) {
                        $property->{$itemKey}[] = $this->convertSimpleXmlElementToObject($item);
                    }
                }
            } else {
                $property = (object)[];
                $property->value = $value->__toString();
                $property->attributes = (object)[];
                foreach ($value->attributes() as $attributeKey => $attributeValue) {
                    if ($attributeValue instanceof SimpleXMLElement) {
                        $property->attributes->{$attributeKey} = $attributeValue->__toString();
                    }
                }
            }
            $object->{$key} = $property;
        }
        return $object;
    }

    /**
     * @param $url
     * @return object
     */
    private function getUrl($url)
    {
        if ($this->cache->get($url)) {
            $response = $this->cache->get($url);
        } else {
            $response = $this->convertSimpleXmlElementToObject($this->client->get($url)->xml());
            $response = $response->query;
            $this->cache->set($url, $response);
        }
        return $response;
    }

    /**
     * @param string $url
     * @return string
     */
    public function getRawContent($url)
    {
        return $this->getUrl($url);
    }

    /**
     * @param string $url
     * @return string
     */
    public function getRawRevision($url)
    {
        $response = $this->getUrl($url);
        return $response->pages[0]->page->revisions[0]->rev->value;
    }

    /**
     * @param string $url
     * @param string $path
     * @return string
     */
    public function getRevision($url, $path = null)
    {
        $response = $this->getUrl($url);
        $content = $response->pages[0]->page->revisions[0]->rev->value;
        $wiki = new WikipediaParser($content);
        $content = $wiki->parse();
        if ($path) {
            return $this->getContentPathRecursive($content, explode('.', $path));
        } else {
            return $content;
        }
    }

    /**
     * @param array $content
     * @param array $paths
     * @return string
     */
    private function getContentPathRecursive(array $content, array $paths)
    {
        $currentPath = current($paths);
        $remainingPaths = array_slice($paths, 1);
        if (isset($content[$currentPath])) {
            if (count($remainingPaths)) {
                return $this->getContentPathRecursive($content[$currentPath], $remainingPaths);
            } else {
                return $content[$currentPath];
            }
        }
        return null;
    }

    /**
     * @param string $query
     * @return array|null
     */
    public function getSearchResult($query)
    {
        $url = sprintf(self::SEARCH_URL, urlencode($query));
        $response = $this->getUrl($url);

        $results = [];
        var_dump($url, $response->search);die();
        foreach ($response->query->search->p as $result) {
            $result = current((array)$result);
            $results[] = isset($result->title) ? $result->title : null;
        }
        return $results;
    }
}
