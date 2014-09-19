<?php
namespace SmartData\Factory;

class SupportedLanguageCollection
{
    /**
     * @var array
     */
    private $supportedLanguages = [
        'en',
        'fr',
        'de'
    ];

    /**
     * @return array
     */
    public function getSupportedLanguages()
    {
        return $this->supportedLanguages;
    }

    /**
     * @param array $supportedLanguages
     * @return $this
     */
    public function setSupportedLanguages($supportedLanguages)
    {
        $this->supportedLanguages = $supportedLanguages;
        return $this;
    }
}
