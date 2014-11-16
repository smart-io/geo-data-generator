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
use SmartData\SmartDataGenerator\SourceCommand\CreateCommand as SourceCreateCommand;
use SmartData\SmartDataGenerator\CountryDatabase\CreateCommand as CountryDatabaseCreateCommand;
use SmartData\SmartDataGenerator\RegionDatabase\Command\CreateDatabaseCommand as RegionDatabaseCreateCommand;
use SmartData\SmartDataGenerator\Upload\UploadCommand;

$application = new Application();
$application->add(new SourceCreateCommand());
$application->add(new CountryDatabaseCreateCommand());
$application->add(new RegionDatabaseCreateCommand());
$application->add(new UploadCommand());
$application->run();
