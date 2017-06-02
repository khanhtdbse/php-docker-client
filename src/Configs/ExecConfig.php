<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 13:25
 */

namespace DockerClient\Configs;

use DockerClient\Exceptions\Execs\Exec400Exception;

class ExecConfig extends Config
{
    protected $configs = [];
    private $execName;
    private $defaultConfigs = [
        "AttachStdin"  => true,
        "AttachStdout" => true,
        "AttachStderr" => true,
        "DetachKeys"   => "ctrl-p,ctrl-q",
        "Tty"          => true,
    ];

    public function __construct(array $configs = [])
    {
        $this->validate($configs);

        $this->configs = $configs;
    }

    protected function validate($configs): bool
    {
        return true;
    }

    public function getName(): string
    {
        return $this->execName;
    }

    protected function parseConfigs(): array
    {
        return array_merge($this->defaultConfigs, $this->configs);
    }
}