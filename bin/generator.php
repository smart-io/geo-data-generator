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
use Smart\Geo\Generator\Meta\Command\GenerateMetaCommand;
use Smart\Geo\Generator\DataGenerator\Country\Command\GenerateDataCommand as GenerateCountryDataCommand;
use Smart\Geo\Generator\DataGenerator\Region\Command\GenerateDataCommand as GenerateRegionDataCommand;
use Smart\Geo\Generator\Uploader\Command\UploadCommand;

$container = new \Smart\Geo\Generator\Container();

$application = new Application();
$application->add(new GenerateMetaCommand($container));
$application->add(new GenerateCountryDataCommand($container));
$application->add(new GenerateRegionDataCommand($container));
$application->add(new UploadCommand($container));
$application->run();
