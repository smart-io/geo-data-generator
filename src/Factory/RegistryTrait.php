<?php
namespace SmartData\Factory;

use Sinergi\Config\Config as SinergiConfig;

trait RegistryTrait
{
    /**
     * @return Registry
     */
    abstract function getRegistry();

    /**
     * @return SinergiConfig
     */
    public function getConfig()
    {
        return $this->getRegistry()->getConfig();
    }

    /**
     * @param SinergiConfig $config
     * @return $this
     */
    public function setConfig(SinergiConfig $config)
    {
        $this->getRegistry()->setConfig($config);
        return $this;
    }
}
