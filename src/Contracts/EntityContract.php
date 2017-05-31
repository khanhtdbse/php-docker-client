<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 31/05/2017
 * Time: 14:36
 */

namespace DockerClient\Contracts;

interface EntityContract
{
    public function getEntityType();

    public function getEntityID();

    public function getManager();

    public function setEntityType(string $entityType);

    public function setEntityID(string $entityID);

    public function setManager(string $manager);
}