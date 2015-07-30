<?php

namespace Smart\Geo\Generator\Uploader;

use Smart\Geo\Generator\Container;
use Smart\Geo\Generator\Meta\MetaMapper;
use Smart\Geo\Generator\Meta\MetaPersister;
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
        $this->addAdditionalFiles($localStorage);
        exec("rsync -avz --delete -e ssh '{$localStorage}/' '{$server}:{$path}'");
        $this->removeAdditionalFiles($localStorage);
    }

    /**
     * @param $dir
     */
    private function addAdditionalFiles($dir)
    {
        $meta = $this->container->getConfig()->getMetaStorage() . DIRECTORY_SEPARATOR . MetaPersister::JSON_FILE;
        copy($meta, $dir . DIRECTORY_SEPARATOR . MetaPersister::JSON_FILE);
        file_put_contents($dir . DIRECTORY_SEPARATOR . '.gitignore', "*" . PHP_EOL . "!.gitignore" . PHP_EOL);
    }

    /**
     * @param $dir
     */
    private function removeAdditionalFiles($dir)
    {
        unlink($dir . DIRECTORY_SEPARATOR . MetaPersister::JSON_FILE);
        unlink($dir . DIRECTORY_SEPARATOR . '.gitignore');
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
     * @return array
     * @throws \Exception
     */
    private function checkMeta()
    {
        $localStorage = $this->container->getConfig()->getGeneratorStorage();

        $metaData = (new MetaPersister($this->container))->loadMeta();
        $metaData = (new MetaMapper)->mapCollectionFromArray($metaData);

        $metaDirs = [];
        foreach ($metaData as $meta) {
            if (stripos($meta->getProvider(), 'smartdataprovider.com')) {
                $metaDirs[] = $meta->getPath();
                $file = $localStorage . '/' . $meta->getPath() . '/' . $meta->getFilename();
                if (!is_file($file)) {
                    throw new \Exception("Failed: Some files are not generated, use the generate command");
                }

                if ($meta->getComponents()) {
                    $data = json_decode(file_get_contents($file), true);

                    foreach ($meta->getComponents() as $componentName => $component) {
                        foreach ($data as $entry) {
                            $key = $entry[$component['key']];
                            $entryFile = $localStorage . '/' .
                                sprintf($component['path'], $key) . '/' .
                                sprintf($component['filename'], $key);
                            if (!is_file($entryFile)) {
                                throw new \Exception("Failed: Some files are not generated, use the generate command");
                            }
                        }
                    }
                }
            }
        }
        return $metaDirs;
    }
}
