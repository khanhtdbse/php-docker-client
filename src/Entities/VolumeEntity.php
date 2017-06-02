<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 12:53
 */

namespace DockerClient\Entities;
use DockerClient\Transporters\Volume;

class VolumeEntity extends Entity
{
    public function __construct($volumeID)
    {
        parent::__construct();

        if ($volumeID instanceof \stdClass) {
            $volumeID = $volumeID->Name;
        }

        $this->setEntityID($volumeID);
        $this->setEntityType('volumes');
        $this->setManager(Volume::class);
        $this->getAttributes();
    }
}