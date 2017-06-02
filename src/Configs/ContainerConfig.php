<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 13:25
 */

namespace DockerClient\Configs;

use DockerClient\Entities\ImageEntity;

class ContainerConfig extends Config
{
    protected $configs = [];
    private $containerName;

    public function __construct(string $containerName, array $configs)
    {
        $this->validate($containerName, $configs);
        $this->containerName = $containerName;
        $this->configs = $configs;
    }

    protected function validate($containerName, $configs): bool
    {
        if (!isset($configs['Image'])) throw new \Exception("attribute `Image` is required!");
        if (!is_string($configs['Image']) &&
            !$configs['Image'] instanceof ImageEntity
        ) throw new \Exception("attribute `Image` must be a image name or instance of ImageEntity class!");

        return true;
    }

    public function getName()
    {
        return $this->containerName;
    }

    protected function parseConfigs(): array
    {
        if ($this->configs['Image'] instanceof ImageEntity) {
            $this->configs['Image'] = $this->configs['Image']->getEntityID();
        }
        return $this->configs;
    }
}