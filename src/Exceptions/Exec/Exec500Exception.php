<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 01/06/2017
 * Time: 14:45
 */

namespace DockerClient\Exceptions\Exec;


use DockerClient\Entities\VolumeEntity;
use Throwable;

class Exec500Exception extends \Exception
{
    public function __construct($msg, $code, Throwable $previous = null)
    {
        parent::__construct($msg, $code, $previous);
    }


}