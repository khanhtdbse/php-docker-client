<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 01/06/2017
 * Time: 07:56
 */
namespace DockerClient\Tests;

use DockerClient\Configs\ContainerConfig;
use DockerClient\Configs\ExecConfig;
use DockerClient\Entities\ContainerEntity;
use DockerClient\Transporters\Container;


class ExecTest extends BaseTestCase
{
    public function testExecCreate()
    {
        $containerConfig = new ContainerConfig($this->getRandomContainerName(),[
            'Image' => 'nginx'
        ]);
        $container = new Container();

        $container->create($containerConfig);
        $container->start();

        $execConfig = new ExecConfig([
           'Cmd' => ['echo','hellotest']
        ]);
        $result = $container->exec($execConfig);

        dd(self::$testVolumeName,self::$testContainerName,self::$testImageName);

        $this->assertEquals('hellotest', $result);
    }
}