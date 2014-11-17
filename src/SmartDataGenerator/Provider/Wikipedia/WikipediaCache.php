<?php
namespace SmartData\SmartDataGenerator\Provider\Wikipedia;

class WikipediaCache
{
    const CACHE_KEY = '42zGe8NPuwTJ4P8B';
    const PERIOD = 3600;

    /**
     * @return string
     */
    private function getCacheDir()
    {
        return realpath(__DIR__ . "/../../../../storage/cache");
    }

    /**
     * @param string $key
     * @return string
     */
    private function getCacheFile($key)
    {
        $key = self::CACHE_KEY . hash('ripemd160', $key);
        return $this->getCacheDir() . DIRECTORY_SEPARATOR. $key;
    }

    public function voidCache()
    {
        foreach (scandir($this->getCacheDir()) as $item) {
            if (substr($item, 0, strlen(self::CACHE_KEY)) === self::CACHE_KEY) {
                unlink($this->getCacheDir() . DIRECTORY_SEPARATOR . $item);
            }
        }
    }

    /**
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        $file = $this->getCacheFile($key);
        if (file_exists($file)) {
            return file_get_contents($file);
        }
        return null;
    }

    /**
     * @param string $key
     * @param string $reponse
     */
    public function set($key, $reponse)
    {
        file_put_contents($this->getCacheFile($key), $reponse);
    }
}
