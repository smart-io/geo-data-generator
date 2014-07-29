<?php
namespace SmartData\Factory;

class Preference
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $isLoaded = false;

    /**
     * @var array
     */
    private $preferences = [];

    public function __construct()
    {
        $this->path = __DIR__ . '/../../storage/preferences/preferences.json';
        if (!is_dir(dirname($this->path))) {
            mkdir(dirname($this->path), 0777, true);
        }
    }

    private function load()
    {
        if (is_file($this->path)) {
            $content = file_get_contents($this->path);
            $this->preferences = json_decode($content, true);
        }
        $this->isLoaded = true;
    }

    /**
     * @param $key
     * @return null|mixed
     */
    public function get($key)
    {
        if (!$this->isLoaded) {
            $this->load();
        }

        if (isset($this->preferences[$key])) {
            return $this->preferences[$key];
        }
        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->preferences[$key] = $value;
        $this->save();
    }

    private function save()
    {
        $content = json_encode($this->preferences, JSON_PRETTY_PRINT);
        file_put_contents($this->path, $content);
    }
}
