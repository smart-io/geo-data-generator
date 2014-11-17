<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Region\Command;

use SmartData\SmartDataGenerator\Command;
use SmartData\SmartDataGenerator\DataGenerator\Region\RegionDataGenerator;
use SmartData\SmartDataGenerator\Provider\Wikipedia\WikipediaCache;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDataCommand extends Command
{
    protected function configure()
    {
        $this->setName('region:database:create')
            ->addOption('void-cache', 'vc')
            ->setDescription('Create the Region Database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Creating the Region Database: ', true);

        if ($input->getOption('void-cache')) {
            (new WikipediaCache())->voidCache();
        }

        $generator = new RegionDataGenerator($this->getContainer());
        $regions = $generator->genereteAllRegion();

        var_dump($regions);

        die();

        $mapper = new RegionMapper($this->getRegistry());
        $mapper->mapArrayToJson($countriesArray);

        $output->write('[ <fg=green>OK</fg=green> ]', true);
    }
}
