<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 12:53
 */

namespace DockerClient\Entities;


use DockerClient\Contracts\EntityContract;
use DockerClient\Traits\HttpTrait;

class Entity implements EntityContract
{
    use HttpTrait;

    public $entityType;
    public $entityID;
    public $manager;
    public $attributes;

    public function refresh()
    {
        $this->getAttributes();
    }

    public function getAttributes()
    {
        $this->setAttributes($this->inspect());
    }

    public function setAttributes($attributes)
    {
        $values = [];
        if (is_array($attributes)) {
            $values = $attributes;
        } elseif ($attributes instanceof \stdClass) {
            $values = (new \ArrayObject($attributes))->getArrayCopy();
        }
        $this->attributes = $values;
    }

    public function getEntityType()
    {
        return $this->entityType;
    }

    public function setEntityType(string $entityType)
    {
        $this->entityType = $entityType;
    }

    public function getEntityID()
    {
        return $this->entityID;
    }

    public function setEntityID(string $entityID)
    {
        $this->entityID = $entityID;
    }

    public function __call($name, $arguments)
    {
        $managerName = $this->getManager();
        $managerInstance = new $managerName;
        $managerInstance->setEntity($this);
        return $managerInstance->$name($arguments);
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function setManager(string $manager)
    {
        $this->manager = $manager;
    }
}