<?php
namespace SmartData\Factory\Wikipedia;

use GuzzleHttp\Client;

class WikipediaGetter
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
     * @param $url
     * @return object
     */
    private function getUrl($url)
    {
        if ($this->cache->get($url)) {
            $response = $this->cache->get($url);
        } else {
            $response = json_decode(json_encode($this->client->get($url)->xml()));
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
        $response = $this->getUrl($url);

        if (isset($response->query) && isset($response->query->pages->page->revisions->rev)) {
            return (string)$response->query->pages->page->revisions->rev;
        }
        return null;
    }

    /**
     * @param string $url
     * @param string $path
     * @return string
     */
    public function getRevision($url, $path = null)
    {
        $response = $this->getUrl($url);

        if (isset($response->query) && isset($response->query->pages->page->revisions->rev)) {
            $content = (string)$response->query->pages->page->revisions->rev;
            $wiki = new WikipediaParser($content);
            $content = $wiki->parse();
            if ($path) {
                return $this->getContentPathRecursive($content, explode('.', $path));
            } else {
                return $content;
            }
        }
        return null;
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

        if (isset($response->query) && isset($response->query->search)) {
            $results = [];
            foreach ($response->query->search->p as $result) {
                $result = current((array)$result);
                $results[] = isset($result->title) ? $result->title : null;
            }
            return $results;
        }
        return null;
    }
}
