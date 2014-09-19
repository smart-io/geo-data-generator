<?php
namespace SmartData\Factory\CountryDatabase;

use SmartData\Factory\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    protected function configure()
    {
        $this->setName('country:database:create')
            ->setDescription('Create the Country Database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Creating the Country Database: ', true);

        $parser = new CountryListParser();
        $countriesArray = $parser->parseCountryListPage($output);

        $mapper = new CountryMapper($this->getRegistry());
        $mapper->mapArrayToJson($countriesArray);

        $output->write('[ <fg=green>OK</fg=green> ]', true);
    }
}
