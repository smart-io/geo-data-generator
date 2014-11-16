<?php
namespace SmartData\SmartDataGenerator\Meta;

class MetaLoader
{
    const JSON_FILE = 'meta.json';

    /**
     * @param string $storage
     * @return array
     */
    public function loadMetaJson($storage)
    {
        $content = file_get_contents($storage . DIRECTORY_SEPARATOR . self::JSON_FILE);
        return json_decode($content, true);
    }
}
