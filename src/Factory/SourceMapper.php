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
        return [
            'version' => $source->getVersion(),
            'type' => $source->getType(),
            'url' => $source->getUrl()
        ];
    }
}
