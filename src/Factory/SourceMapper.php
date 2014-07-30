<?php
namespace SmartData\Factory;

class SourceMapper
{
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
        return $retval;
    }
}
