<?php

namespace Smart\Geo\Generator\Dist;

use Exception;
use Smart\Geo\Generator\Container;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Smart\Geo\Data\DataUpdater;

class Dist
{
    const PATH = __DIR__ . "/../../vendor/smart/geo/storage";

    /**
     * @var Container
     */
    private $container;

    /**
     * @var QuestionHelper
     */
    private $question;

    public function __construct(
        Container $container,
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $question
    )
    {
        $this->container = $container;
        $this->input = $input;
        $this->output = $output;
        $this->question = $question;
    }

    public function create()
    {
        (new DataUpdater())->update();
        $this->check();
        $this->compress();
        $this->move();
    }

    public function check()
    {
        if (!is_dir(self::PATH . "/countries") || !is_dir(self::PATH . "/geolite2") || !is_dir(self::PATH . "/regions")) {
            throw new Exception("Data was not downloaded successfully");
        }
        return true;
    }

    public function compress()
    {
        chdir(self::PATH);
        exec("tar -cvzf geo.tar.gz *");
    }

    public function move()
    {
        if (!is_dir(__DIR__ . "/../../storage/generator")) {
            mkdir(__DIR__ . "/../../storage/generator", 0777, true);
        }
        if (is_file(__DIR__ . "/../../storage/generator/geo.tar.gz")) {
            unlink(__DIR__ . "/../../storage/generator/geo.tar.gz");
        }
        rename(self::PATH . "/geo.tar.gz", __DIR__ . "/../../storage/generator/geo.tar.gz");
    }
}
