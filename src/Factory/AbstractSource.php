<?php
namespace SmartData\Factory;

abstract class AbstractSource implements SourceInterface
{
    /**
     * @return null|string
     */
    public function getType()
    {
        if (defined('static::TYPE')) {
            return static::TYPE;
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        if (defined('static::URL')) {
            return static::URL;
        }
        return null;
    }
}
