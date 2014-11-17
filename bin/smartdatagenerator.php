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
use SmartData\SmartDataGenerator\DataGenerator\Country\Command\GenerateDataCommand as GenerateCountryDataCommand;
use SmartData\SmartDataGenerator\DataGenerator\Region\Command\GenerateDataCommand as GenerateRegionDataCommand;
//use SmartData\SmartDataGenerator\Upload\UploadCommand;

$container = new \SmartData\SmartDataGenerator\Container();

$application = new Application();
//$application->add(new SourceCreateCommand());
$application->add(new GenerateCountryDataCommand($container));
$application->add(new GenerateRegionDataCommand($container));
//$application->add(new UploadCommand());
$application->run();
