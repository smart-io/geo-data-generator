<?php
namespace SmartData\SmartDataGenerator\Meta;

class MetaMapper
{
    /**
     * @param MetaInterface $meta
     * @return array
     */
    public function mapObjectToArray(MetaInterface $meta)
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
     * @param array $array
     * @return Meta[]
     */
    public function mapCollectionFromArray(array $array)
    {
        $metaCollection = [];
        foreach ($array as $parameters) {
            $metaCollection[] = new Meta($parameters);
        }
        return $metaCollection;
    }
}
