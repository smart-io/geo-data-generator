<?php
namespace SmartData\SmartDataGenerator\RegionDatabase\WikipediaRegion;

use SmartData\SmartDataGenerator\Wikipedia\WikipediaGetter;

class WikipediaRegionNameParser
{
    const NAMES_URL =
        'http://en.wikipedia.org/w/api.php?action=query&titles=%s&prop=langlinks&lllimit=500&format=xml&continue';

    /**
     * @var WikipediaGetter
     */
    private $wikipediaGetter;

    public function __construct()
    {
        $this->wikipediaGetter = new WikipediaGetter();
    }

    /**
     * @param array $region
     */
    public function parseRegion(array $region)
    {
        $url = sprintf(self::NAMES_URL, $region['link']);
        $content = $this->wikipediaGetter->getRawContent($url);
        var_dump($content->query->pages->page->langlinks->ll);

        return;

        $url = sprintf(self::NAME_URL, trim($countryName));
        /** @var \GuzzleHttp\Message\ResponseInterface $response */
        $response = $this->client->get($url);
        $response = $response->xml();

        $names = [];
        if (isset($response->query) && isset($response->query->pages->page->langlinks)) {
            $names['en'] = trim((string)$response->query->pages->page->attributes()['title']);

            /** @var SimpleXMLElement $name */
            foreach ($response->query->pages->page->langlinks->ll as $name) {
                $language = trim((string)$name->attributes()['lang']);
                $names[$language] = trim((string)$name);
            }
        } else {
            trigger_error('Could not find country names for ' . $countryName, E_USER_ERROR);
        }
        return $names;

        var_dump($link);
        die();
    }
}
