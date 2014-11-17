<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Country\WikipediaCountry;

use SmartData\SmartDataGenerator\Registry;

class CountryMapper
{
    const JSON_FILENAME = 'countries/countries.json';
    const COUNTRY_JSON_FILENAME = 'countries/countries/%s.json';

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
     * @param array $countries
     * @return array
     */
    public function mapArrayToJson(array $countries)
    {
        $languages = $this->registry->getSupportedLanguageCollection();
        $jsonFile =  $this->registry->getConfig()->getFactoryStorage() . '/' . self::JSON_FILENAME;

        $json = [];
        foreach ($countries as $country) {
            $names = [];
            foreach ($languages->getSupportedLanguages() as $language) {
                if (isset($country['names'][$language])) {
                    $names[$language] = $country['names'][$language];
                }
            }

            $value = [
                'shortCode' => $country['codes']['iso'],
                'code' => $country['codes']['code'],
                'names' => $names,
                'latitude' => $country['coordinates']['latitude'],
                'longitude' => $country['coordinates']['longitude'],
                'boundariesNortheastLatitude' => $country['coordinates']['boundaries']['northeast']['latitude'],
                'boundariesNortheastLongitude' => $country['coordinates']['boundaries']['northeast']['longitude'],
                'boundariesSouthwestLatitude' => $country['coordinates']['boundaries']['southwest']['latitude'],
                'boundariesSouthwestLongitude' => $country['coordinates']['boundaries']['southwest']['longitude'],
            ];

            $json[] = $value;
        }

        $dir = dirname($jsonFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $countryJsonFile =  $this->registry->getConfig()->getFactoryStorage() . '/' . self::COUNTRY_JSON_FILENAME;
        $dir = dirname($countryJsonFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if ($files = scandir($dir)) {
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    unlink($dir . '/' . $file);
                }
            }
        }

        foreach ($json as $country) {
            $file = sprintf($countryJsonFile, $country['shortCode']);
            file_put_contents($file, json_encode($country));
        }

        file_put_contents($jsonFile, json_encode($json));
        return $json;
    }
}
