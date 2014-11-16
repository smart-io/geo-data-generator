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
//use SmartData\SmartDataGenerator\SourceCommand\CreateCommand as SourceCreateCommand;
//use SmartData\SmartDataGenerator\CountryDatabase\CreateCommand as CountryDatabaseCreateCommand;
use SmartData\SmartDataGenerator\DataGenerator\Region\Command\GenerateDataCommand as GenerateRegionDataCommand;
//use SmartData\SmartDataGenerator\Upload\UploadCommand;

$application = new Application();
//$application->add(new SourceCreateCommand());
//$application->add(new CountryDatabaseCreateCommand());
$application->add(new GenerateRegionDataCommand());
//$application->add(new UploadCommand());
$application->run();
