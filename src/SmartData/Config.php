<?php
namespace Flighthub\SmartData;

class Config
{
    /**
     * @var string
     */
    private $dataDirectory;

    /**
     * @param array $params
     */
    public function __construct(array $params = null)
    {
        if (null !== $params) {
            $this->set($params);
        }
    }

    /**
     * @param array|string $params
     * @param null|mixed $value
     */
    public function set($params, $value = null)
    {
        if (!is_array($params)) {
            $params = array($params => $value);
        }
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                switch ($key) {
                    case 'dataDirectory':
                    case 'data_directory':
                        $this->setDataDirectory($value);
                        break;
                }
            }
        }
    }

    /**
     * @param string $key
     * @return null|mixed
     */
    public function get($key)
    {
        switch ($key) {
            case 'dataDirectory':
            case 'data_directory':
                return $this->getDataDirectory();
                break;
        }
        return null;
    }

    /**
     * @return string
     */
    public function getDataDirectory()
    {
        return $this->dataDirectory;
    }

    /**
     * @param string $dataDirectory
     * @return $this
     */
    public function setDataDirectory($dataDirectory)
    {
        $this->dataDirectory = $dataDirectory;
        return $this;
    }
}