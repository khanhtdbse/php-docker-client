<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 13:25
 */

namespace DockerClient\Configs;

use PHPUnit\Runner\Exception;

class VolumeConfig extends Config
{
    protected $configs = [];
    private $volumeName;

    public function __construct(array $configs = [])
    {
        $this->validate($configs);

        $this->configs = $configs;
    }

    protected function validate($configs): bool
    {
        if (!isset($configs['Name'])) throw new Exception("Name attribute is required!");

        return true;
    }

    public function getName(): string
    {
        return $this->volumeName;
    }

    protected function parseConfigs(): array
    {
        return $this->configs;
    }
}