<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 13:25
 */

namespace DockerClient\Configs;

abstract class Config
{
    protected $configs;

    public function getConfigs($key = '')
    {
        if ($key == '') {
            return $this->parseConfigs();
        }

        return $this->parseConfigs()[$key]??null;
    }

    abstract protected function parseConfigs(): array;

    public function getQueryParams()
    {
        return http_build_query($this->configs);
    }


}