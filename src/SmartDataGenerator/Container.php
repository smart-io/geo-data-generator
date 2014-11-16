<?php
namespace SmartData\SmartDataGenerator;

class Container
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
     * @var LanguageCollection
     */
    private $languageCollection;

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
     * @return LanguageCollection
     */
    public function getLanguageCollection()
    {
        if (null === $this->languageCollection) {
            $this->languageCollection = new LanguageCollection();
        }
        return $this->languageCollection;
    }

    /**
     * @param LanguageCollection $languageCollection
     * @return $this
     */
    public function setSupportedLanguageCollection(LanguageCollection $languageCollection)
    {
        $this->languageCollection = $languageCollection;
        return $this;
    }
}
