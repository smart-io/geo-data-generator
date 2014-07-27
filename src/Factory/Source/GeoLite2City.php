<?php
namespace SmartData\Factory\Source;

use SmartData\Factory\AbstractSource;

class GeoLite2City extends AbstractSource
{
    const VERSION = '0.1.0';
    const TYPE = 'custom';
    const COMPRESSION = 'gzip';
    const URL = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';
}
