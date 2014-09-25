<?php
namespace SmartData\Factory\Source;

use SmartData\Factory\AbstractSource;

class Country extends AbstractSource
{
    const VERSION = '0.1.0';
    const TYPE = 'json';
    const PROVIDER = 'https://smartdataprovider.com/countries/countries.json';
    const PATH = 'countries';
    const FILENAME = 'countries.json';

    protected $components = [
        'country' => [
            'key' => 'shortCode',
            'provider' => 'https://smartdataprovider.com/countries/countries/%s.json',
            'path' => 'countries/countries',
            'filename' => '%s.json',
        ]
    ];
}
