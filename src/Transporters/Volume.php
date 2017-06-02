<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 11:43
 */

namespace DockerClient\Transporters;

use DockerClient\Collections\VolumeCollection;
use DockerClient\Configs\VolumeConfig;
use DockerClient\Contracts\EntityContract;
use DockerClient\Contracts\EntityManagerContract;
use DockerClient\Entities\VolumeEntity;
use DockerClient\Exceptions\Volumes\VolumeException;
use DockerClient\Traits\HttpTrait;
use GuzzleHttp\Exception\RequestException;

class Volume implements EntityManagerContract
{
    use HttpTrait;

    public $volumeEntity;

    public static function ls(): VolumeCollection
    {
        try {
            $instance = new self();
            $request = $instance->http->request("GET", "volumes");
        } catch (RequestException $e) {
            throw new VolumeException($e);
        }

        return VolumeCollection::makeFromResponse($request);
    }

    public function create(VolumeConfig $config): void
    {
        try {
            $request = $this->http->request("POST", "volumes/create", [
                'json' => $config->getConfigs(),
            ]);
        } catch (RequestException $e) {
            throw new VolumeException($e);
        }

        $data = json_decode($request->getBody());

        $volumeID = $data->Name;

        $this->volumeEntity = new VolumeEntity($volumeID);
    }

    public function inspect(): array
    {
        try {
            $request = $this->http->request("GET", $this->getEntity()->getEntityType() . "/" . $this->getEntity()->getEntityID());
        } catch (RequestException $e) {
            throw new VolumeException($e);
        }

        return json_decode($request->getBody()->getContents(), true);
    }

    public function rm(): void
    {
        try {
            $this->http->request("DELETE", "volumes/" . $this->getEntity()->getEntityID());
        } catch (RequestException $e) {
            throw new VolumeException($e);
        }
    }

    public function setEntity(EntityContract $entity)
    {
        $this->volumeEntity = $entity;
    }

    public function getEntity(): EntityContract
    {
        return $this->volumeEntity;
    }
}