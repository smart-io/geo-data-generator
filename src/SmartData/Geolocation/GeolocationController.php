<?php
namespace Flighthub\SmartData\Geolocation;

use Flighthub\SmartData\Config;
use MaxMind\Db\Reader;

class GeolocationController
{
    /**
     * @var string
     */
    private $devIpCacheKey = 'smartdata_dev_ip_cache_key';

    /**
     * @var string
     */
    private $geoliteLocationCacheKey = 'smartdata_geolite_location_cache_key';

    /**
     * @var string
     */
    private $cityDatabaseLocation = '/GeoLite2/GeoLite2-City.mmdb';

    /**
     * @var string
     */
    private $countryDatabaseLocation = '/GeoLite2/GeoLite2-Country.mmdb';

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return GeolocationEntity|null
     */
    public function getGeolocation()
    {
        $geolocation = $this->getIpLocation();
        return $geolocation;
    }

    /**
     * @return GeolocationEntity|null
     */
    public function getIpLocation()
    {
        $ipAddress = $this->getIpAddress();

        if (apc_exists($this->geoliteLocationCacheKey . $ipAddress)) {
            $result = apc_fetch($this->geoliteLocationCacheKey . $ipAddress);
        } else {
            $reader = new Reader($this->config->getDataDirectory() . $this->cityDatabaseLocation);
            $result = $reader->get($ipAddress);
            $reader->close();
            if (is_array($result)) {
                apc_store($this->geoliteLocationCacheKey . $ipAddress, $result);
            }
        }

        if (is_array($result)) {
            if (isset($result['location']['latitude']) && isset($result['location']['longitude'])) {
                $geoLocation = new GeolocationEntity();
                $geoLocation->setSource(GeolocationEntity::SOURCE_IP);
                $geoLocation->setLatitude($result['location']['latitude']);
                $geoLocation->setLongitude($result['location']['longitude']);
                return $geoLocation;
            }
        }

        return null;
    }

    /**
     * @return mixed|null|string
     */
    private function getIpAddress()
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return null;
    }

    /**
     * @param $localIp
     * @return null|string
     * @internal
     */
    private function getDevIpAddress($localIp)
    {
        if (apc_exists($this->devIpCacheKey . $localIp)) {
            return apc_fetch($this->devIpCacheKey . $localIp);
        }
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, 'http://icanhazip.com');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $ipAddress = trim(curl_exec($handle));
        curl_close($handle);

        if (!empty($ipAddress) && preg_match('/^[\d\.]*$/', $ipAddress)) {
            apc_store($this->devIpCacheKey . $localIp, $ipAddress, 60 * 60 * 2);
            return $ipAddress;
        }
        return null;
    }
}