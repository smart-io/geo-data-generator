<?php
namespace SmartData\SmartDataGenerator\Uploader;

use SmartData\SmartDataGenerator\Container;
use SmartData\SmartDataGenerator\Meta\MetaFile;
use SmartData\SmartDataGenerator\Meta\MetaMapper;
use SmartData\SmartDataGenerator\Meta\MetaPersister;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class Uploader
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var QuestionHelper
     */
    private $question;

    /**
     * @param Container $container
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $question
     */
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

    public function uploadAll()
    {
        $this->checkMeta();
        list($server, $path) = $this->getPreferences();

        $localStorage = realpath($this->container->getConfig()->getGeneratorStorage());
        exec("rsync -rave ssh {$localStorage}/* {$server}:{$path}");

    }

    /**
     * @return array
     */
    private function getPreferences()
    {
        $preference = $this->container->getPreference();

        $providerRemote = $preference->get('provider_remote');
        $useProviderRemoteQuestion = new ConfirmationQuestion(
            "Use remote {$providerRemote['server']} ({$providerRemote['path']}) [Y/n]: "
        );

        if (
            null !== $providerRemote &&
            $this->question->ask($this->input, $this->output, $useProviderRemoteQuestion)
        ) {
            $server = $providerRemote['server'];
            $path = $providerRemote['path'];
        } else {
            $remoteServerQuestion = new Question("Remote server: ");
            $remotePathQuestion = new Question("Path on remote server: ");

            $server = $this->question->ask($this->input, $this->output, $remoteServerQuestion);
            $path = $this->question->ask($this->input, $this->output, $remotePathQuestion);

            $preference->set('provider_remote', ['server' => $server, 'path' => $path]);
        }

        return [$server, $path];
    }

    /**
     * @throws \Exception
     */
    private function checkMeta()
    {
        $localStorage = $this->container->getConfig()->getGeneratorStorage();

        $metaData = (new MetaPersister($this->container))->loadMeta();
        $metaData = (new MetaMapper)->mapCollectionFromArray($metaData);

        foreach ($metaData as $meta) {
            if (stripos($meta->getProvider(), 'smartdataprovider.com')) {
                $file = $localStorage . '/' . $meta->getPath() . '/' . $meta->getFilename();
                if (!is_file($file)) {
                    throw new \Exception("Failed: Some files are not generated, use the generate command");
                }

                if ($meta->getComponents()) {
                    $data = json_decode(file_get_contents($file), true);

                    foreach ($meta->getComponents() as $componentName => $component) {
                        foreach ($data as $entry) {
                            $key = $entry[$component['key']];
                            $entryFile = $localStorage . '/' . $component['path'] . '/' .
                                sprintf($component['filename'], $key);
                            if (!is_file($entryFile)) {
                                throw new \Exception("Failed: Some files are not generated, use the generate command");
                            }
                        }
                    }
                }
            }
        }
    }
}
