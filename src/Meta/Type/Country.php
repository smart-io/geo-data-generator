<?php

namespace Smart\Geo\Generator\Meta\Type;

use Smart\Geo\Generator\Meta\AbstractMeta;

class Country extends AbstractMeta
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
        ],
        'country/polygon' => [
            'key' => 'shortCode',
            'provider' => 'https://smartdataprovider.com/countries/countries/%s/polygon.json',
            'path' => 'countries/countries/%s',
            'filename' => 'polygon.json',
        ]
    ];
}
