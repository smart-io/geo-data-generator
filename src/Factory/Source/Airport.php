<?php
namespace SmartData\Factory\Source;

use SmartData\Factory\AbstractSource;

class Airport extends AbstractSource
{
    const VERSION = '0.1.0';
    const TYPE = 'json';
    const PROVIDER = 'https://smartdataprovider.com/airports/airports.json';
    const PATH = 'airports';
    const FILENAME = 'airports.json';
}
