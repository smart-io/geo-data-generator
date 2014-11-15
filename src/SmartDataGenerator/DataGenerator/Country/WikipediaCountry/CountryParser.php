<?php
namespace SmartData\Factory\CountryDatabase;

use SimpleXMLElement;
use GuzzleHttp\Client;
use SmartData\Factory\WikiParser;

class CountryParser
{
    const NAME_URL =
        'http://en.wikipedia.org/w/api.php?action=query&titles=%s&prop=langlinks&lllimit=500&format=xml';
    const INFOBOXES_URL =
        'http://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=xml&titles=%s&rvsection=0';
    const ISO_CODES_URL =
        'http://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=4&format=xml&titles=ISO_3166-1';
    const GEOLOCATION_URL =
        'http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false';
    const WIKIPEDIA_PAGE_URL =
        'http://en.wikipedia.org/wiki/%s';

    private static $isoCodesCache;

    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param array $country
     * @return array
     */
    public function parseCountryPage(array $country)
    {
        $link = str_replace('/wiki/', '', $country['link']);

        $info = $this->parseCountryInfoboxes($link);
        $names = $this->getCountryNames($link);
        $codes = $this->getCountryCodes($country['name'], $names, $link, $info);
        $coordinates = $this->getCountryCoordinates($names['en']);

        if (empty($names)) {
            trigger_error('Could not find country names for ' . $country['name'], E_USER_ERROR);
        }

        if (!empty($codes) && !empty($coordinates)) {
            return [
                'names' => $names,
                'info' => $info,
                'codes' => $codes,
                'coordinates' => $coordinates
            ];
        }

        return null;
    }

    /**
     * @param string $countryName
     * @return array
     */
    public function parseCountryInfoboxes($countryName)
    {
        $url = sprintf(self::INFOBOXES_URL, $countryName);

        /** @var \GuzzleHttp\Message\ResponseInterface $response */
        $response = $this->client->get($url);
        $response = $response->xml();

        $info = [];
        if (isset($response->query) && isset($response->query->pages->page->revisions->rev)) {
            // Redirects
            if (preg_match_all(
                "/\\{\\{redirect2?\\|([^\\}]*)\\}\\}/",
                $response->query->pages->page->revisions->rev,
                $matches
            )) {
                if (isset($matches[1][0])) {
                    $info['other_names'] = explode('|', $matches[1][0]);
                }
            }

            $content = (string)$response->query->pages->page->revisions->rev;
            $wiki = new WikiParser($content);
            $content = $wiki->parse();
            if (empty($content['infoboxes']) && !empty($content['intro_section'])) {
                $info = $this->parseRawCountryInfoboxes($content['intro_section']);
            } else {
                $content = current($content['infoboxes'])['contents'];

                if (isset($content['conventional_long_name'])) {
                    $info['conventional_long_name'] = current($content['conventional_long_name']);
                }

                if (isset($content['common_name'])) {
                    $info['common_name'] = current($content['common_name']);
                }

                if (isset($content['country_code'])) {
                    $info['country_code'] = current($content['country_code']);
                }

                if (isset($content['iso3166code'])) {
                    $info['iso3166code'] = current($content['iso3166code']);
                }
            }
        }

        if (empty($info)) {
            //trigger_error('Could not find country information for ' . $countryName, E_USER_ERROR);
        }

        return $info;
    }

    /**
     * @param $content
     * @return array
     */
    public function parseRawCountryInfoboxes($content)
    {
        return [];
    }

    /**
     * @param string $countryName
     * @return array
     */
    public function getCountryNames($countryName)
    {
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
    }

    /**
     * @param string $name
     * @param array $names
     * @param string $link
     * @param array $info
     * @param bool $searchDeeper
     * @return string
     */
    public function getCountryCodes($name, array $names, $link, array $info, $searchDeeper = true)
    {
        $original = [$name, $names, $link, $info];

        if (null === self::$isoCodesCache) {
            /** @var \GuzzleHttp\Message\ResponseInterface $response */
            $response = $this->client->get(self::ISO_CODES_URL);
            $response = $response->xml();
            self::$isoCodesCache = $response;
        } else {
            $response = self::$isoCodesCache;
        }

        if (isset($response->query) && isset($response->query->pages->page->revisions->rev)) {
            $content = (string)$response->query->pages->page->revisions->rev;
            $wiki = new WikiParser($content);
            $content = $wiki->parse();

            $wiki = new WikiParser(trim($content['majorSections'][0]['text']));
            $content = $wiki->parse()['intro_section'];

            preg_match_all("/\\|-\n\\| .*\n\\| .*\n/", $content, $matches);

            $englishName = $names['en'];
            $names = array_merge($names, [
                $name,
                $link,
                isset($info['conventional_long_name']) ? $info['conventional_long_name'] : null,
                isset($info['common_name']) ? $info['common_name'] : null,
            ]);
            if (isset($info['other_names'])) {
                $names = array_merge($names, $info['other_names']);
            }
            if (stripos($englishName, 'the ') !== false) {
                $names[] = trim(str_ireplace('the ', '', $englishName));
                $parts = preg_split('/the /i', $englishName, 2);
                $names[] = $parts[1] . ', the ' . $parts[0];
                $names[] = $parts[1] . ', ' . $parts[0];
            }

            $isoCode = isset($info['iso3166code']) ? $info['iso3166code'] : null;

            $matches = current($matches);
            $codes = [];
            foreach ($matches as $match) {
                if (null !== $isoCode && stripos($match, "ISO 3166-2:{$isoCode}") !== false) {
                    preg_match("/\\[ISO 3166-2:([\\w]{2,2})\\]/", $match, $isoCode);
                    if (isset($isoCode[1])) {
                        $codes['iso'] = $isoCode[1];
                    }
                    preg_match("/<tt>([\\w]{3,3})<\\/tt>/", $match, $countryCode);
                    if (isset($countryCode[1])) {
                        $codes['code'] = $countryCode[1];
                    }
                    return $codes;
                }

                foreach ($names as $name) {
                    if (!empty($name) && strpos($match, trim($name)) !== false) {
                        preg_match("/\\[ISO 3166-2:([\\w]{2,2})\\]/", $match, $isoCode);
                        if (isset($isoCode[1])) {
                            $codes['iso'] = $isoCode[1];
                        }
                        preg_match("/<tt>([\\w]{3,3})<\\/tt>/", $match, $countryCode);
                        if (isset($countryCode[1])) {
                            $codes['code'] = $countryCode[1];
                        }
                        return $codes;
                    }
                }
            }
        }

        if ($searchDeeper) {
            $isoCode = $this->getIsoCountryCodesFromWikiPage($link);
            if ($isoCode) {
                $original[3]['iso3166code'] = $isoCode;
                return $this->getCountryCodes($original[0], $original[1], $original[2], $original[3], false);
            }
        }

        return null;
    }

    /**
     * @param string $link
     * @return mixed|null
     */
    public function getIsoCountryCodesFromWikiPage($link)
    {
        $url = sprintf(self::WIKIPEDIA_PAGE_URL, urlencode($link));

        /** @var \GuzzleHttp\Message\ResponseInterface $response */
        $response = $this->client->get($url);
        $response = (string)$response->getBody();

        if (preg_match('/ISO_3166\\-2\\:(\\w){2,2}/', $response, $matches)) {
            return str_replace('ISO_3166-2:', '', $matches[0]);
        }

        return null;
    }

    /**
     * @param string $countryName
     * @return array
     */
    public function getCountryCoordinates($countryName)
    {
        $url = sprintf(self::GEOLOCATION_URL, urlencode($countryName));
        $result = json_decode(file_get_contents($url), true);
        if (
            isset($result['results'][0]['geometry']['bounds']) &&
            isset($result['results'][0]['geometry']['location'])
        ) {
            $bounds = $result['results'][0]['geometry']['bounds'];
            $location = $result['results'][0]['geometry']['location'];
            return [
                'boundaries' => [
                    'northeast' => [
                        'latitude' => (string)$bounds['northeast']['lat'],
                        'longitude' => (string)$bounds['northeast']['lng'],
                    ],
                    'southwest' => [
                        'latitude' => (string)$bounds['southwest']['lat'],
                        'longitude' => (string)$bounds['southwest']['lng'],
                    ]
                ],
                'latitude' => (string)$location['lat'],
                'longitude' => (string)$location['lng']
            ];
        }

        return null;
    }
}
