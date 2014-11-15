<?php
namespace SmartData\Factory;

class SourceFactory
{
    /**
     * @var SourceMapper
     */
    private $sourceMapper;

    /**
     * @param SourceInterface $source
     * @return array
     */
    public function create(SourceInterface $source)
    {
        $mapper = $this->getSourceMapper();
        return $mapper->mapToJson($source);
    }

    /**
     * @return SourceMapper
     */
    public function getSourceMapper()
    {
        if (null === $this->sourceMapper) {
            $this->sourceMapper = new SourceMapper();
        }
        return $this->sourceMapper;
    }
}
