<?php
namespace SmartData\SmartDataGenerator\Source;

use SmartData\SmartDataGenerator\AbstractSource;

class GeoLite2City extends AbstractSource
{
    const VERSION = '0.1.0';
    const TYPE = 'custom';
    const COMPRESSION = 'gzip';
    const PROVIDER = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';
    const PATH = 'geolite2';
    const FILENAME = 'GeoLite2-City.mmdb';
}
