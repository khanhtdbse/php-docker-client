<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 13:25
 */

namespace DockerClient;

class ImageConfig extends Config
{
    protected $configs = [];
    private $imageName;
    private $compressedContextPath;

    public function __construct(string $compressedContextPath, array $configs = [])
    {
        $this->validate($compressedContextPath, $configs);

        $this->configs = $configs;
        $this->compressedContextPath = $compressedContextPath;
    }

    protected function validate($compressedContextPath, $configs): bool
    {
        if (!isset($configs['t'])) throw new \Exception("attribute `t` is required!");

        if (isset($configs['remote']) && false !== filter_var($configs['remote'], FILTER_VALIDATE_URL)) throw new \Exception("remote Dockerfile is not a valid URL");

        if (!file_exists($compressedContextPath)) throw new \Exception("compressedContextPath does not exist");
        if (!is_readable($compressedContextPath)) throw new \Exception("compressedContextPath is not readable");

        if (false === strpos($compressedContextPath, 'tar.bz2')
            && false === strpos($compressedContextPath, 'tar.gz')
            && false === strpos($compressedContextPath, 'tar.xz')
        ) throw new \Exception("compressed format is not supported");


        return true;
    }

    public function getName(): string
    {
        return $this->imageName;
    }

    public function getRawCompressContext(): string
    {
        return file_get_contents($this->compressedContextPath);
    }

    protected function parseConfigs(): array
    {
        return $this->configs;
    }
}