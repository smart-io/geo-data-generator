<?php
namespace SmartData\Factory;

interface SourceInterface
{
    public function getType();
    public function getUrl();
    public function getVersion();
    public function getCompression();
    public function getProvider();
    public function getFilename();
}
