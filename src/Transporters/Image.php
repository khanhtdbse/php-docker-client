<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 11:43
 */

namespace DockerClient\Transporters;

use DockerClient\Collections\ImageCollection;
use DockerClient\Configs\ImageConfig;
use DockerClient\Contracts\EntityContract;
use DockerClient\Contracts\EntityManagerContract;
use DockerClient\Entities\ImageEntity;
use DockerClient\Exceptions\Images\ImageException;
use DockerClient\Traits\HttpTrait;
use GuzzleHttp\Exception\RequestException;

class Image implements EntityManagerContract
{
    use HttpTrait;

    public $imageEntity;

    public static function ls(): ImageCollection
    {
        try {
            $instance = new self();
            $request = $instance->http->request("GET", "images/json");
        } catch (RequestException $e) {
            throw new ImageException($e);
        }

        return ImageCollection::makeFromResponse($request);
    }

    public function build(ImageConfig $config): void
    {
        try {
            $this->http->request("POST", "build?" . $config->getQueryParams(), [
                'body' => $config->getRawCompressContext()
            ]);
        } catch (RequestException $e) {
            throw new ImageException($e);
        }

        $this->setEntity(new ImageEntity($config->getConfigs('t')));
    }

    public function inspect(): array
    {
        try {
            $request = $this->http->request("GET", $this->getEntity()->getEntityType() . "/" . $this->getEntity()->getEntityID() . "/json");
        } catch (RequestException $e) {
            throw new ImageException($e);
        }
        return json_decode($request->getBody()->getContents(), true);
    }

    public function rm(): void
    {
        try {
            $this->http->request("DELETE", "images/" . $this->getEntity()->getEntityID());
        } catch (RequestException $e) {
            throw new ImageException($e);
        }
    }

    public function setEntity(EntityContract $entity)
    {
        $this->imageEntity = $entity;
    }

    public function getEntity(): EntityContract
    {
        return $this->imageEntity;
    }
}