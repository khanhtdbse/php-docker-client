<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 12:53
 */

namespace DockerClient\Entities;
use DockerClient\Transporters\Exec;

class ExecEntity extends Entity
{
    public function __construct($execID)
    {
        parent::__construct();

        if ($execID instanceof \stdClass) {
            $execID = $execID->Id;
        }

        $this->setEntityID($execID);
        $this->setEntityType('exec');
        $this->setManager(Exec::class);
        $this->getAttributes();
    }
}