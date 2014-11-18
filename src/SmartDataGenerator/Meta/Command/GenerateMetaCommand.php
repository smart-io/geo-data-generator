<?php
namespace SmartData\SmartDataGenerator\Meta\Command;

use SmartData\SmartDataGenerator\Command;
use SmartData\SmartDataGenerator\Meta\MetaGenerator;
use SmartData\SmartDataGenerator\Meta\MetaWriter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMetaCommand extends Command
{
    protected function configure()
    {
        $this->setName('meta:create')
            ->setDescription('Create the meta information JSON file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Creating the meta information JSON file: ');

        $metaData = (new MetaGenerator($this->getContainer()))->generateAllMeta();
        (new MetaWriter($this->getContainer()))->writeMeta($metaData);
        $output->write('[ <fg=green>OK</fg=green> ]', true);

        var_dump($metaData);
        return;

        $file = $this->getRegistry()->getConfig()->getFactoryStorage();
        if (!is_dir($file)) {
            mkdir($file, 0777, true);
        }
        file_put_contents("{$file}/source.json", json_encode($source, JSON_PRETTY_PRINT));
        $output->write('[ <fg=green>OK</fg=green> ]', true);
    }
}
