<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Region;

use SmartData\SmartDataGenerator\Container;

class RegionDataWriter
{
    const LIST_JSON_FILENAME = 'regions/regions.json';
    const ITEM_JSON_FILENAME = 'regions/regions/%s.json';
    const POLYGON_DATA_FILENAME = 'regions/regions/%s/polygon.json';

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function writeAllRegion($regions)
    {
        $output = [];
        foreach ($regions as $region) {
            $output[$region['code']] = $this->mapRegionToArray($region);
        }
        $this->doWrite(array_values($output));
    }

    /**
     * @param array $output
     */
    private function doWrite($output)
    {
        $listFile =
            $this->container->getConfig()->getGeneratorStorage() . DIRECTORY_SEPARATOR . self::LIST_JSON_FILENAME;
        $this->mkdir($listFile);
        file_put_contents($listFile, json_encode($output));

        foreach ($output as $item) {
            $itemFile =
                $this->container->getConfig()->getGeneratorStorage() . DIRECTORY_SEPARATOR .
                sprintf(self::ITEM_JSON_FILENAME, $item['code']);
            $this->mkdir($itemFile);
            file_put_contents($itemFile, json_encode($item));
        }
    }

    /**
     * @param string $filename
     */
    private function mkdir($filename)
    {
        $dir = dirname($filename);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    /**
     * @param array $region
     * @return array
     */
    private function mapRegionToArray($region)
    {
        return [
            'names' => $this->mapLanguagesToArray($region),
            'code' => $region['code'],
            'long_code' => $region['long_code'],
            'country' => $region['country'],
            'type' => $region['type'],
            'timezone' => $region['timezone'],
            'bounding_box' => $region['bounding_box'],
            'latitude' => $region['latitude'],
            'longitude' => $region['longitude'],
        ];
    }

    /**
     * @param array $region
     * @return array
     */
    private function mapLanguagesToArray($region)
    {
        $retval = [];
        foreach ($this->container->getLanguageCollection()->getLanguages() as $language) {
            if (isset($region['names'][$language])) {
                $retval[$language] = $region['names'][$language];
            } else {
                $retval[$language] = $region['name'];
            }
        }
        return $retval;
    }
}
