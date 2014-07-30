<?php
namespace SmartData\Factory\Upload;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SmartData\Factory\Command;

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

        $localStorage = $this->getRegistry()->getConfig()->getFactoryStorage();
        $fileList = require 'FileList.php';
        foreach ($fileList as $file) {
            if (!is_file("{$localStorage}/{$file}")) {
                $output->write(
                    '<error>Failed: Some files are not generated, use the generate command</error>',
                    true
                );
                return;
            }
        }

        $preference = $this->getRegistry()->getPreference();

        $provierRemote = $preference->get('provider_remote');
        if (
            null !== $provierRemote &&
            $this->dialog->askConfirmation(
                $output,
                "Use remote {$provierRemote['server']} ({$provierRemote['path']}) [Y/n]: ",
                false
            )
        ) {
             $server = $provierRemote['server'];
             $path = $provierRemote['path'];
        } else {
            $server = $this->dialog->ask($output, 'Remote server: ');
            $path = $this->dialog->ask($output, 'Path on remote server: ');

            $preference->set('provider_remote', ['server' => $server, 'path' => $path]);
        }

        $output->write('Uploading files: ');

        $localStorage = realpath($localStorage);
        exec("rsync -rave ssh {$localStorage}/* {$server}:{$path}");
        $output->write('[ <fg=green>DONE</fg=green> ]', true);
    }
}