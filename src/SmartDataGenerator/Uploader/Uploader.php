<?php
namespace SmartData\SmartDataGenerator\Uploader;

use SmartData\SmartDataGenerator\Container;

class Uploader
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function uploadAll()
    {
        $localStorage = $this->container->getConfig()->getGeneratorStorage();

        $sourceMapper = new SourceMapper();
        $sources = $sourceMapper->mapFromJson($this->getRegistry()->getConfig());

        foreach ($sources as $source) {
            if (stripos($source->getProvider(), 'smartdataprovider.com')) {
                $file = $localStorage . '/' . $source->getPath() . '/' . $source->getFilename();
                if (!is_file($file)) {
                    $output->write(
                        '<error>Failed: Some files are not generated, use the generate command</error>',
                        true
                    );
                    return;
                }

                if ($source->getComponents()) {
                    $data = json_decode(file_get_contents($file), true);

                    foreach ($source->getComponents() as $componentName => $component) {
                        foreach ($data as $entry) {
                            $key = $entry[$component['key']];
                            $entryFile = $localStorage . '/' . $component['path'] . '/' .
                                sprintf($component['filename'], $key);
                            if (!is_file($entryFile)) {
                                $output->write(
                                    '<error>Failed: Some files are not generated, use the generate command</error>',
                                    true
                                );
                                return;
                            }
                        }
                    }
                }
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

    }
}
