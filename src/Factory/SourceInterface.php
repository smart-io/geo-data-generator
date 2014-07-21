<?php
namespace SmartData\Factory;

interface SourceInterface
{
    public function getType();
    public function getUrl();
    public function getVersion();
}
