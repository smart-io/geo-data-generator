<?php

$vendor = realpath(__DIR__ . '/../vendor');

if (file_exists($vendor . "/autoload.php")) {
    require_once $vendor . "/autoload.php";
} else {
    $vendor = realpath(__DIR__ . '/../../../');
    if (file_exists($vendor . "/autoload.php")) {
        require_once $vendor . "/autoload.php";
    } else {
        throw new Exception("Unable to load dependencies");
    }
}

use Symfony\Component\Console\Application;
use SmartData\Factory\SourceCommand\CreateCommand as SourceCreateCommand;
use SmartData\Factory\AirportDatabase\CreateCommand as AirportDatabaseCreateCommand;

$application = new Application();
$application->add(new SourceCreateCommand());
$application->add(new AirportDatabaseCreateCommand());
$application->run();
