<?php
namespace SmartData\SmartDataGenerator\Meta;

class Meta extends AbstractMeta
{
    private $type;
    private $url;
    private $version;
    private $compression;
    private $provider;
    private $filename;
    private $path;
    private $components;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->set($attributes);
    }

    /**
     * @param array $attributes
     */
    public function set(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            switch ($key) {
                case 'type':
                    $this->type = $value;
                    break;
                case 'url':
                    $this->url = $value;
                    break;
                case 'version':
                    $this->version = $value;
                    break;
                case 'compression':
                    $this->compression = $value;
                    break;
                case 'provider':
                    $this->provider = $value;
                    break;
                case 'filename':
                    $this->filename = $value;
                    break;
                case 'path':
                    $this->path = $value;
                    break;
                case 'components':
                    $this->components = $value;
                    break;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * @return mixed
     */
    public function getCompression()
    {
        return $this->compression;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }
}
