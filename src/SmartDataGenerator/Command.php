<?php
namespace SmartData\Factory;

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

abstract class Command extends SymfonyCommand
{
    use RegistryTrait;

    /**
     * @var DialogHelper
     */
    protected $dialog;

    /**
     * @var Registry
     */
    private $registry;

    public function __construct()
    {
        $this->dialog = new DialogHelper;
        parent::__construct();
    }

    /**
     * @return Registry
     */
    public function getRegistry()
    {
        if (null === $this->registry) {
            $this->registry = new Registry();
        }
        return $this->registry;
    }

    /**
     * @param Registry $registry
     * @return $this
     */
    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;
        return $this;
    }
}
