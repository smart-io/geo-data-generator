<?php

namespace Smart\Geo\Generator\Uploader\Command;

use Smart\Geo\Generator\Uploader\Uploader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Smart\Geo\Generator\Command;

class UploadCommand extends Command
{
    protected function configure()
    {
        $this->setName('upload')
            ->setDescription('Upload generated data to remote provider');
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

        $output->write('Uploading files: ');

        (new Uploader(
            $this->getContainer(),
            $input,
            $output,
            $this->question
        ))->uploadAll();

        $output->write('[ <fg=green>DONE</fg=green> ]', true);
    }
}
