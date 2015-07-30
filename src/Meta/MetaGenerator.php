<?php

namespace Smart\Geo\Generator\Meta;

use Smart\Geo\Generator\Container;

class MetaGenerator
{
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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
        $metaMapper = new MetaMapper();
        foreach ($classes as $class) {
            $meta[] = $metaMapper->mapObjectToArray(new $class);
        }

        return $meta;
    }
}
