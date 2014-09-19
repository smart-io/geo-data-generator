<?php
namespace SmartData\Factory\AirportDatabase;

use SmartData\Factory\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    protected function configure()
    {
        $this->setName('airport:database:create')
            ->setDescription('Create the Airport Database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Creating the Airport Database: ');

        $mapper = new Mapper($this->getRegistry());
        $mapper->mapXmlToJson();

        $output->write('[ <fg=green>OK</fg=green> ]', true);
        //$output->write('[ <error>Failed: Process is already runnning</error> ]', true);
    }
}
