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

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $localStorage = $this->getRegistry()->getConfig()->getStorage();

        $output->write('Uploading files: ');
        foreach (require 'FileList.php' as $file) {
            exec("scp {$localStorage}/{$file} {$server}:{$path}/{$file}");
        }
        $output->write('[ <fg=green>DONE</fg=green> ]', true);
    }
}