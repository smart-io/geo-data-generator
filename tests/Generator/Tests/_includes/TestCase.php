<?php

namespace Smart\Geo\Generator\Tests;

use PHPUnit_Framework_TestCase;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @return \Smart\Geo\Generator\Container
     */
    public function getContainer()
    {
        global $container;
        return $container;
    }
}
