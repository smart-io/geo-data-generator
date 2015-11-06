<?php

namespace Smart\Geo\Generator\Dist\Command;

use Smart\Geo\Generator\Dist\Dist;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Smart\Geo\Generator\Command;

class DistCommand extends Command
{
    protected function configure()
    {
        $this->setName('dist')
            ->setDescription('Create distribution packages');
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

        $output->write('Create distribution packages: ');

        (new Dist(
            $this->getContainer(),
            $input,
            $output,
            $this->question
        ))->create();

        $output->write('[ <fg=green>DONE</fg=green> ]', true);
    }
}
