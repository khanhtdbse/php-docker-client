<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 01/06/2017
 * Time: 07:56
 */
namespace DockerClient\Tests;

use DockerClient\Collections\ImageCollection;
use DockerClient\Configs\ImageConfig;
use DockerClient\Entities\ImageEntity;
use DockerClient\Transporters\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends BaseTestCase
{
    public function testListImages()
    {
        $listImage = Image::ls();
        $this->assertInstanceOf(ImageCollection::class, $listImage);
    }


    public function testBuildImage($imageName = null)
    {
        $imageName = $imageName??$this->getRandomImageName();

        $image = new Image();
        $imageConfig = new ImageConfig('compressWithDockerfile.tar.bz2', [
            't' => $imageName,
            'rm' => false
        ]);
        $image->build($imageConfig);

        $this->assertTrue(true);

        return $image->getEntity();
    }

    /**
     * @depends testBuildImage
     */
    public function testInspectImage(ImageEntity $imageEntity)
    {
        $result = $imageEntity->inspect();
        $this->assertTrue(is_array($result));
    }

    /**
     * @depends testBuildImage
     */
    public function testRemoveImage(ImageEntity $imageEntity)
    {
        $imageEntity->rm();
        $this->assertTrue(true);
    }

    /**
     * @expectedException \DockerClient\Exceptions\Images\Image404Exception
     */
    public function test404Exception(){
        $imageEntity = new ImageEntity(rand(999,99999));
        $imageEntity->inspect();
    }

    /**
     * @expectedException \DockerClient\Exceptions\Images\Image400Exception
     */
    public function test400Exception(){
        $imageName = $imageName??$this->getRandomImageName();

        $image = new Image();
        $imageConfig = new ImageConfig('compressWithDocdkerfile.tar.bz2', [
            't' => $imageName,
            'r-m' => "failvalue",
            '??r-m' => "failvalue"
        ]);
        $image->build($imageConfig);

        $this->assertTrue(true);
    }

}