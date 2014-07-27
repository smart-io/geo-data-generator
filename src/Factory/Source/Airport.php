<?php
namespace SmartData\Factory\Source;

use SmartData\Factory\AbstractSource;

class Airport extends AbstractSource
{
    const VERSION = '0.1.0';
    const TYPE = 'json';
    const COMPRESSION = 'zip';
    const PROVIDER = 'https://data.smartdataprovider.com/';
    const FILENAME = 'airport';
}
