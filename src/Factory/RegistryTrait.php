<?php
namespace SmartData\Factory;

use Sinergi\Config\Config;

trait RegistryTrait
{
    /**
     * @return Registry
     */
    abstract function getRegistry();

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->getRegistry()->getConfig();
    }

    /**
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->getRegistry()->setConfig($config);
        return $this;
    }
}
