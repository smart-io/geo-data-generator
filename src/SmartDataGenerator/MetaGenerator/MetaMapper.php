<?php
namespace SmartData\Factory;

class SourceMapper
{
    const JSON_FILE = 'source.json';

    /**
     * @param SourceInterface $source
     * @return array
     */
    public function mapToJson(SourceInterface $source)
    {
        $retval = [];
        if ($source->getType()) {
            $retval['type'] = $source->getType();
        }
        if ($source->getUrl()) {
            $retval['url'] = $source->getUrl();
        }
        if ($source->getVersion()) {
            $retval['version'] = $source->getVersion();
        }
        if ($source->getCompression()) {
            $retval['compression'] = $source->getCompression();
        }
        if ($source->getProvider()) {
            $retval['provider'] = $source->getProvider();
        }
        if ($source->getFilename()) {
            $retval['filename'] = $source->getFilename();
        }
        if ($source->getPath()) {
            $retval['path'] = $source->getPath();
        }
        if ($source->getComponents()) {
            $retval['components'] = $source->getComponents();
        }
        return $retval;
    }

    /**
     * @param Config $config
     * @return Source[]
     */
    public function mapFromJson(Config $config)
    {
        $sourceCollection = [];
        $content = file_get_contents($config->getFactoryStorage() . '/' . self::JSON_FILE);
        $content = json_decode($content, true);
        foreach ($content as $source) {
            $sourceCollection[] = new Source($source);
        }
        return $sourceCollection;
    }
}
