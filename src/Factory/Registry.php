<?php
namespace SmartData\Factory;

class Registry
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Preference
     */
    private $preference;

    /**
     * @var SupportedLanguageCollection
     */
    private $supportedLanguageCollection;

    /**
     * @return Config
     */
    public function getConfig()
    {
        if (null === $this->config) {
            $this->config = new Config();
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

    /**
     * @return Preference
     */
    public function getPreference()
    {
        if (null === $this->preference) {
            $this->preference = new Preference();
        }
        return $this->preference;
    }

    /**
     * @param Preference $preference
     * @return $this
     */
    public function setPreference(Preference $preference)
    {
        $this->preference = $preference;
        return $this;
    }

    /**
     * @return SupportedLanguageCollection
     */
    public function getSupportedLanguageCollection()
    {
        if (null === $this->supportedLanguageCollection) {
            $this->supportedLanguageCollection = new SupportedLanguageCollection();
        }
        return $this->supportedLanguageCollection;
    }

    /**
     * @param SupportedLanguageCollection $supportedLanguageCollection
     * @return $this
     */
    public function setSupportedLanguageCollection(SupportedLanguageCollection $supportedLanguageCollection)
    {
        $this->supportedLanguageCollection = $supportedLanguageCollection;
        return $this;
    }
}
