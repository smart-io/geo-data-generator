<?php
namespace SmartData\Factory;

use GuzzleHttp\Client;

class WikiGetter
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param string $url
     * @param string $path
     * @return string
     */
    public function getRevision($url, $path = null)
    {
        /** @var \GuzzleHttp\Message\ResponseInterface $response */
        $response = $this->client->get($url);
        $response = $response->xml();

        if (isset($response->query) && isset($response->query->pages->page->revisions->rev)) {
            $content = (string)$response->query->pages->page->revisions->rev;
            $wiki = new WikiParser($content);
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
}
