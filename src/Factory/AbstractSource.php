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
            return constant('static::TYPE');
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        if (defined('static::URL')) {
            return constant('static::URL');
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getVersion()
    {
        if (defined('static::VERSION')) {
            return constant('static::VERSION');
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getCompression()
    {
        if (defined('static::COMPRESSION')) {
            return constant('static::COMPRESSION');
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getProvider()
    {
        if (defined('static::PROVIDER')) {
            return constant('static::PROVIDER');
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getFilename()
    {
        if (defined('static::FILENAME')) {
            return constant('static::FILENAME');
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        if (defined('static::PATH')) {
            return constant('static::PATH');
        }
        return null;
    }

    /**
     * @return null|array
     */
    public function getComponents()
    {
        if (isset($this->components) && is_array($this->components)) {
            return $this->components;
        }
        return null;
    }
}
