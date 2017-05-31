<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 31/05/2017
 * Time: 14:36
 */

namespace DockerClient\Contracts;

interface EntityManagerContract
{
    public function setEntity(EntityContract $entity);

    public function getEntity(): EntityContract;
}