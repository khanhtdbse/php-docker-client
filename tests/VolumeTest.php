<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 01/06/2017
 * Time: 07:56
 */
namespace DockerClient\Tests;

use DockerClient\Collections\VolumeCollection;
use DockerClient\Configs\VolumeConfig;
use DockerClient\Entities\VolumeEntity;
use DockerClient\Transporters\Volume;

class VolumeTest extends BaseTestCase
{
    public function testListVolumes()
    {
        $listContainer = Volume::ls();
        $this->assertInstanceOf(VolumeCollection::class, $listContainer);
        $this->assertTrue(true);
    }

    public function testCreateNewVolume()
    {
        $volumeConfig = new VolumeConfig([
            'Name' => $this->getRandomVolumeName()
        ]);
        $volume = new Volume();
        $volume->create($volumeConfig);

        $this->assertTrue(true);

        return $volume;
    }

    /**
     * @depends testCreateNewVolume
     */
    public function testInspectVolume()
    {
        $volumeEntity = new VolumeEntity(self::$testVolumeName[0]);
        $result = $volumeEntity->inspect();

        $this->assertTrue(is_array($result));
    }

    /**
     * @depends testCreateNewVolume
     * @expectedException \DockerClient\Exceptions\Volumes\Exec404Exception
     */
    public function testRemoveVolume()
    {
        $volumeEntity = new VolumeEntity(self::$testVolumeName[0]);
        $volumeEntity->rm();

        $volumeEntity->inspect();

        $this->assertTrue(true);
    }

    /**
     * @expectedException \DockerClient\Exceptions\Volumes\Exec404Exception
     */
    public function test404Exception()
    {
        $volumeEntity = new VolumeEntity(rand(999,99999));
        $volumeEntity->inspect();
        $this->assertTrue(true);
    }

}