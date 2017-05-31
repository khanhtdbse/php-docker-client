<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 12:53
 */

namespace DockerClient\Entities;


use DockerClient\Container;

class ContainerEntity extends Entity
{
    public function __construct($containerID)
    {
        parent::__construct();

        if ($containerID instanceof \stdClass) {
            $containerID = $containerID->Id;
        }

        $this->setEntityID($containerID);
        $this->setEntityType('containers');
        $this->setManager(Container::class);
        $this->getAttributes();
    }


}