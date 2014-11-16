<?php
namespace SmartData\SmartDataGenerator\Upload;

use SmartData\SmartDataGenerator\SourceMapper;
use SmartData\SmartDataGenerator\Uploader\Uploader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SmartData\SmartDataGenerator\Command;

class UploadCommand extends Command
{
    protected function configure()
    {
        $this->setName('upload')->setDescription('Upload local factory to remote provider');
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

        $uploader = new Uploader();
        $uploader->uploadAll();


        $output->write('Uploading files: ');

        $localStorage = realpath($localStorage);
        exec("rsync -rave ssh {$localStorage}/* {$server}:{$path}");
        $output->write('[ <fg=green>DONE</fg=green> ]', true);
    }
}
