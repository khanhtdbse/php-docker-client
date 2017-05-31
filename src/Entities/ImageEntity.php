<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 12:53
 */

namespace DockerClient\Entities;

use DockerClient\Image;

class ImageEntity extends Entity
{
    public function __construct($imageEntity)
    {
        parent::__construct();

        if ($imageEntity instanceof \stdClass) {
            $imageEntity = $imageEntity->Id;
        }

        $this->setEntityID($imageEntity);
        $this->setEntityType('images');
        $this->setManager(Image::class);
        $this->getAttributes();
    }
}