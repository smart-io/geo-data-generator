<?php
namespace Flighthub\SmartData\GeoLite2;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends Command
{
    protected function configure()
    {
        $this->setName('geolite2:update')->setDescription('Download and update Maxmind GeoLite2 Database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Downloading Maxmind GeoLite2 Database: ');

        $this->download(
            'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz',
            $this->getApp()->getRootDir() . '/data/GeoLite2'
        );

        $this->download(
            'http://geolite.maxmind.com/download/geoip/database/GeoLite2-Country.mmdb.gz',
            $this->getApp()->getRootDir() . '/data/GeoLite2'
        );

        $output->write('[ <fg=green>DONE</fg=green> ]', true);
    }

    /**
     * @param $source
     * @param $destination
     * @return string
     */
    private function download($source, $destination)
    {
        $file = $this->downloadFile($source);
        $file = $this->uncompress($file);
        return $this->moveFile($file, $destination);
    }

    /**
     * @param $source
     * @return string
     */
    private function downloadFile($source)
    {
        $filename = basename($source);
        $destination = tempnam(sys_get_temp_dir(), 'geolite');

        if (!is_dir($destination)) {
            if (file_exists($destination)) {
                unlink($destination);
            }
            mkdir($destination, 0777, true);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $file = fopen($destination . DIRECTORY_SEPARATOR . $filename, "w+");
        fputs($file, $data);
        fclose($file);

        return $destination . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @param $file
     * @return string
     */
    private function uncompress($file)
    {
        chdir(dirname($file));

        $filename = basename($file);
        $newfilename = preg_replace('/\.gz$/', '', $filename);

        exec("gzip -d \"{$filename}\"");

        return dirname($file) . DIRECTORY_SEPARATOR . $newfilename;
    }

    /**
     * @param $file
     * @param $destination
     * @return string
     */
    private function moveFile($file, $destination)
    {
        $filename = basename($file);

        if (!is_dir($destination)) {
            if (file_exists($destination)) {
                unlink($destination);
            }
            mkdir($destination, 0777, true);
        }

        rename($file, $destination . DIRECTORY_SEPARATOR . $filename);
        return $destination . DIRECTORY_SEPARATOR . $filename;
    }
}