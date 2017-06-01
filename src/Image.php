<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 11:43
 */

namespace DockerClient;

use DockerClient\Collections\ImageCollection;
use DockerClient\Contracts\EntityContract;
use DockerClient\Contracts\EntityManagerContract;
use DockerClient\Entities\ImageEntity;
use DockerClient\Traits\CheckResponseTrait;
use DockerClient\Traits\HttpTrait;

class Image implements EntityManagerContract
{
    use HttpTrait, CheckResponseTrait;

    const SUCCESS_STATUS = [
        'build' => [200],
        'ls' => [200],
        'rm' => [200],
    ];

    const WARNING_STATUS = [

    ];

    public $imageEntity;

    public static function ls(): ImageCollection
    {
        $instance = new self();
        $request = $instance->http->request("GET", "images/json");

        $instance->checkErrorResponse(__FUNCTION__, $request);

        return ImageCollection::makeFromResponse($request);
    }

    public function build(ImageConfig $config): void
    {
        $request = $this->http->request("POST", "build?" . $config->getQueryParams(), [
            'body' => $config->getRawCompressContext()
        ]);

        $this->checkErrorResponse(__FUNCTION__, $request);

        $this->setEntity(new ImageEntity($config->getConfigs('t')));
    }

    public function setEntity(EntityContract $entity)
    {
        $this->imageEntity = $entity;
    }

    public function getEntity(): EntityContract
    {
        return $this->imageEntity;
    }

    public function rm(): void
    {
        $request = $this->http->request("DELETE", "images/" . $this->getEntity()->getEntityID());
        $this->checkErrorResponse(__FUNCTION__, $request);
    }

}