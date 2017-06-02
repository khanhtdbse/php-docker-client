<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 01/06/2017
 * Time: 07:56
 */
namespace DockerClient\Tests;

use DockerClient\Collections\ContainerCollection;
use DockerClient\Configs\ContainerConfig;
use DockerClient\Configs\ImageConfig;
use DockerClient\Configs\VolumeConfig;
use DockerClient\Entities\ContainerEntity;
use DockerClient\Transporters\Container;
use DockerClient\Transporters\Image;
use DockerClient\Transporters\Volume;

class ContainerTest extends BaseTestCase
{

    public function testListContainers()
    {
        $listContainer = Container::ls();
        $this->assertInstanceOf(ContainerCollection::class, $listContainer);
    }


    public function testCreateContainerWithExistImage()
    {
        $containerName = $this->getRandomContainerName();

        $containerConfig = new ContainerConfig($containerName, [
            'Image' => 'nginx'
        ]);
        $container = new Container();
        $container->create($containerConfig);
        // Start
        $container->start();

        $this->assertTrue(true);

        return $container->getEntity();
    }

    /**
     * @depends testCreateContainerWithExistImage
     */
    public function testGetContainerLogs(ContainerEntity $containerEntity){
        $this->assertInternalType('string', $containerEntity->logs());
    }

    /**
     * @depends testCreateContainerWithExistImage
     */
    public function testInspectContainer(ContainerEntity $containerEntity)
    {
        $result = $containerEntity->inspect();
        $this->assertTrue(is_array($result));
    }

    /**
     * @depends testCreateContainerWithExistImage
     */
    public function testTopProcesses(ContainerEntity $containerEntity)
    {
        $result = $containerEntity->top();
        $this->assertTrue(is_array($result));
    }

    /**
     * @depends testCreateContainerWithExistImage
     */
    public function testPauseUnpauseContainer()
    {
        $containerEntity = new ContainerEntity(self::$testContainerName[0]);
        $containerEntity->pause();
        $containerEntity->unpause();
        $this->assertTrue(true);
    }

    public function testCreateContainerWithNewImage()
    {
        $imageName = $this->getRandomImageName();
        $containerName = $this->getRandomContainerName();

        $image = new Image();
        $imageConfig = new ImageConfig('compressWithDockerfile.tar.bz2', [
            't' => $imageName,
            'rm' => false
        ]);
        $image->build($imageConfig);

        $container = new Container();

        $containerConfig = new ContainerConfig(
            $containerName,
            $configs = [
                'Image' => $image->getEntity()
            ]
        );
        $container->create($containerConfig);
        $container->start();

        $this->assertTrue(true);

        return $container->getEntity();
    }


    /**
     * @expectedException \DockerClient\Exceptions\Containers\Container404Exception
     */
    public function test404Exception()
    {
        $containerEntity = new ContainerEntity(rand(999, 99999));
        $containerEntity->inspect();
        $this->assertTrue(true);
    }

    /**
     * @depends testCreateContainerWithNewImage
     * @expectedException \DockerClient\Exceptions\Containers\Container409Exception
     */
    public function test409Exception(ContainerEntity $containerEntity)
    {
        $containerEntity->rm();
        $this->assertTrue(true);
    }

//    public function test500Exception(ContainerEntity $containerEntity)
//    {
//        $containerEntity->start();
//        $containerEntity->start();
//        $this->assertTrue(true);
//    }

    /**
     * @depends testCreateContainerWithExistImage
     */
    public function testKillContainer(ContainerEntity $containerEntity)
    {
        $containerEntity->kill();
        $this->assertTrue(true);
    }

    /**
     * @expectedException  \DockerClient\Exceptions\Containers\Container500Exception
     */
    public function test500Exception(){

        $containerConfig = new ContainerConfig("ssss", [
            'Image' => 'nginx',
            'Volumes' => [] // Wrongs!
        ]);
        $container = new Container();
        $container->create($containerConfig);
        // Start
        $container->start();

        $this->assertTrue(true);
    }


    public function testCreateContainerWithVolume()
    {
        $volumeConfig = new VolumeConfig([
            'Name' => $this->getRandomVolumeName()
        ]);
        $volume = new Volume();
        $volume->create($volumeConfig);

        $containerName = $this->getRandomContainerName();

        $containerConfig = new ContainerConfig($containerName, [
            'Image' => 'nginx',
            'HostConfig' => [
                'Mounts' => [
                    [
                        "Target" => "/code",
                        "Source" => $volume->getEntity()->getEntityID(),
                        "Type" => "volume"
                    ]
                ]
            ]
        ]);
        $container = new Container();
        $container->create($containerConfig);
        // Start
        $container->start();

        $this->assertTrue(true);
    }

}