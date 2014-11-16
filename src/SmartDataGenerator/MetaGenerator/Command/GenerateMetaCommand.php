<?php
namespace SmartData\SmartDataGenerator\SourceCommand;

use SmartData\SmartDataGenerator\SourceFactory;
use SmartData\SmartDataGenerator\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    protected function configure()
    {
        $this->setName('source:create')
            ->setDescription('Create the sources JSON file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Creating the sources JSON file: ');

        $classes = [];
        $dir = scandir(__DIR__ . "/../Source");
        foreach ($dir as $file) {
            if ($file !== '.' && $file !== '..') {
                $filename = str_replace('.php', '', $file);
                $classes[] = '\\SmartData\\Factory\\Source\\' . $filename;
            }
        }

        $source = [];
        $sourceFactory = new SourceFactory();
        foreach ($classes as $class) {
            $source[] = $sourceFactory->create(new $class);
        }

        $file = $this->getRegistry()->getConfig()->getFactoryStorage();
        if (!is_dir($file)) {
            mkdir($file, 0777, true);
        }
        file_put_contents("{$file}/source.json", json_encode($source, JSON_PRETTY_PRINT));
        $output->write('[ <fg=green>OK</fg=green> ]', true);
    }
}
