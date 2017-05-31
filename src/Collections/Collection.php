<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 13:02
 */

namespace DockerClient\Collections;

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection as BaseCollection;

abstract class Collection extends BaseCollection
{
    protected $items = [];

    abstract public static function makeFromResponse(Response $response);
}