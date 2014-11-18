<?php
namespace SmartData\SmartDataGenerator\Meta;

use SmartData\SmartDataGenerator\Container;

class MetaGenerator
{
    /**
     * @var MetaMapper
     */
    private $metaMapper;

    public function __construct(Container $container)
    {

    }

    /**
     * @return array
     */
    public function generateAllMeta()
    {
        $classes = [];
        $dir = scandir(__DIR__ . "/../Meta/Type");
        foreach ($dir as $file) {
            if ($file !== '.' && $file !== '..') {
                $filename = str_replace('.php', '', $file);
                $classes[] = '\\SmartData\\SmartDataGenerator\\Meta\\Type\\' . $filename;
            }
        }

        $meta = [];
        foreach ($classes as $class) {
            $meta[] = $this->getMetaMapper()->mapToJson(new $class);
        }

        return $meta;
    }

    /**
     * @return MetaMapper
     */
    public function getMetaMapper()
    {
        if (null === $this->metaMapper) {
            $this->metaMapper = new MetaMapper();
        }
        return $this->metaMapper;
    }
}
