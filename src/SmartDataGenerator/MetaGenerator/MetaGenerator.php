<?php
namespace SmartData\SmartDataGenerator\MetaGenerator;

class MetaGenerator
{
    /**
     * @var MetaMapper
     */
    private $metaMapper;

    /**
     * @return array
     */
    public function generateAllMeta()
    {
        $mapper = $this->getMetaMapper();
        return $mapper->mapToJson($meta);
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
