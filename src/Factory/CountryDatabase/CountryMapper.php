<?php
namespace SmartData\Factory\CountryDatabase;

use SmartData\Factory\Registry;

class CountryMapper
{
    const JSON_FILENAME = 'countries/countries.json';

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
        file_put_contents($jsonFile, json_encode($json));
        return $json;
    }
}
