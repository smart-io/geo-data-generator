<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Country\Command;

use SmartData\SmartDataGenerator\Command;
use SmartData\SmartDataGenerator\DataGenerator\Country\CountryDataGenerator;
use SmartData\SmartDataGenerator\Provider\OpenStreetMap\OpenStreetMapCache;
use SmartData\SmartDataGenerator\Provider\Wikipedia\WikipediaCache;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDataCommand extends Command
{
    protected function configure()
    {
        $this->setName('country:database:create')
            ->addOption('void-cache', 'vc')
            ->setDescription('Create the Country Database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        if ($input->getOption('void-cache')) {
            (new OpenStreetMapCache())->voidCache();
            (new WikipediaCache())->voidCache();
        }

        $output->write('Creating the Country Database: ', true);

        $generator = new CountryDataGenerator($this->getContainer());
        $countries = $generator->genereteAllCountries();

        //(new RegionDataWriter($this->getContainer()))->writeAllRegion($regions);

        $output->write('[ <fg=green>OK</fg=green> ]', true);
/*
        $parser = new CountryListParser();
        $countriesArray = $parser->parseCountryListPage($output);

        $mapper = new CountryMapper($this->getRegistry());
        $mapper->mapArrayToJson($countriesArray);

        $output->write('[ <fg=green>OK</fg=green> ]', true);*/
    }
}
