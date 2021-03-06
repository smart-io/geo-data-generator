<?php

namespace Smart\Geo\Generator\Meta\Type;

use Smart\Geo\Generator\Meta\AbstractMeta;

class GeoLite2City extends AbstractMeta
{
    const VERSION = '0.1.0';
    const TYPE = 'custom';
    const COMPRESSION = 'gzip';
    const PROVIDER = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';
    const PATH = 'geolite2';
    const FILENAME = 'GeoLite2-City.mmdb';
}
