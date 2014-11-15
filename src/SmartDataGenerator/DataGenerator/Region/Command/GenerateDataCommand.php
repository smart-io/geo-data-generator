<?php
namespace SmartData\Factory\RegionDatabase\Command;

use SmartData\Factory\Command;
use SmartData\Factory\RegionDatabase\RegionRepository;
use SmartData\Factory\Wikipedia\WikipediaCache;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends Command
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

        $regionMapper = new RegionRepository();
        $regions = $regionMapper->fetchAll();

        var_dump($regions);

        die();

        $mapper = new RegionMapper($this->getRegistry());
        $mapper->mapArrayToJson($countriesArray);

        $output->write('[ <fg=green>OK</fg=green> ]', true);
    }
}
