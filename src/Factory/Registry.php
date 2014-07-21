<?php
namespace SmartData\Factory;

use Sinergi\Config\Config;

class Registry
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @return Config
     */
    public function getConfig()
    {
        if (null === $this->config) {
            $this->config = new Config(__DIR__ . "/../../config");
        }
        return $this->config;
    }

    /**
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }
}
