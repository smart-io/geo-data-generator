<?php
namespace SmartData\SmartDataGenerator\Meta;

use SmartData\SmartDataGenerator\Config;

class MetaMapper
{
    /**
     * @param MetaInterface $source
     * @return array
     */
    public function mapToJson(MetaInterface $meta)
    {
        $retval = [];
        if ($meta->getType()) {
            $retval['type'] = $meta->getType();
        }
        if ($meta->getUrl()) {
            $retval['url'] = $meta->getUrl();
        }
        if ($meta->getVersion()) {
            $retval['version'] = $meta->getVersion();
        }
        if ($meta->getCompression()) {
            $retval['compression'] = $meta->getCompression();
        }
        if ($meta->getProvider()) {
            $retval['provider'] = $meta->getProvider();
        }
        if ($meta->getFilename()) {
            $retval['filename'] = $meta->getFilename();
        }
        if ($meta->getPath()) {
            $retval['path'] = $meta->getPath();
        }
        if ($meta->getComponents()) {
            $retval['components'] = $meta->getComponents();
        }
        return $retval;
    }

    /**
     * @param Config $config
     * @return Meta[]
     */
    public function mapFromJson(Config $config)
    {
        $metaCollection = [];
        foreach ((new MetaLoader)->loadMetaJson($config->getGeneratorStorage()) as $parameters) {
            $metaCollection[] = new Meta($parameters);
        }
        return $metaCollection;
    }
}
