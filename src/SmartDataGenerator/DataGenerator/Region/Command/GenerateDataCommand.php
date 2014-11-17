<?php
namespace SmartData\SmartDataGenerator\DataGenerator\Region\Command;

use SmartData\SmartDataGenerator\Command;
use SmartData\SmartDataGenerator\DataGenerator\Region\RegionDataGenerator;
use SmartData\SmartDataGenerator\DataGenerator\Region\RegionDataWriter;
use SmartData\SmartDataGenerator\Provider\OpenStreetMap\OpenStreetMapCache;
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
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $output->write('Creating the Region Database: ');

        if ($input->getOption('void-cache')) {
            (new OpenStreetMapCache())->voidCache();
            (new WikipediaCache())->voidCache();
        }

        $generator = new RegionDataGenerator($this->getContainer());
        $regions = $generator->genereteAllRegion();
        (new RegionDataWriter($this->getContainer()))->writeAllRegion($regions);

        $output->write('[ <fg=green>OK</fg=green> ]', true);
    }
}
