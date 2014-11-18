<?php
namespace SmartData\SmartDataGenerator\Meta\Type;

use SmartData\SmartDataGenerator\Meta\AbstractMeta;

class Region extends AbstractMeta
{
    const VERSION = '0.1.0';
    const TYPE = 'json';
    const PROVIDER = 'https://smartdataprovider.com/regions/regions.json';
    const PATH = 'regions';
    const FILENAME = 'regions.json';

    protected $components = [
        'region' => [
            'key' => 'code',
            'provider' => 'https://smartdataprovider.com/regions/regions/%s.json',
            'path' => 'regions/regions',
            'filename' => '%s.json',
        ]
    ];
}
