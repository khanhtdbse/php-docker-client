<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 11:43
 */

namespace DockerClient;

use DockerClient\Collections\ContainerCollection;
use DockerClient\Contracts\EntityContract;
use DockerClient\Contracts\EntityManagerContract;
use DockerClient\Entities\ContainerEntity;
use DockerClient\Traits\CheckResponseTrait;
use DockerClient\Traits\HttpTrait;

class Container implements EntityManagerContract
{
    use HttpTrait, CheckResponseTrait;

    const SUCCESS_STATUS = [
        'ls' => [200],
        'top' => [200],
        'create' => [201],
        'start' => [204],
        'kill' => [204],
        'pause' => [204],
        'unpause' => [204],
        'stop' => [204],
        'rm' => [204],
    ];

    const WARNING_STATUS = [
        'start' => [304],
    ];

    protected $containerEntity;

    public static function ls(): ContainerCollection
    {
        $instance = new self();
        $request = $instance->http->request("GET", "containers/json");
        $instance->checkErrorResponse(__FUNCTION__, $request);

        $collection = ContainerCollection::makeFromResponse($request);

        return $collection;
    }

    public function setEntity(EntityContract $containerEntity): Container
    {
        $this->containerEntity = $containerEntity;
        return $this;
    }

    public function getEntity(): EntityContract
    {
        return $this->containerEntity;
    }

    public function create(ContainerConfig $config): void
    {
        $request = $this->http->request("POST", "containers/create?name=" . $config->getName(), [
            'json' => $config->getConfigs(),
        ]);

        $this->checkErrorResponse(__FUNCTION__, $request);

        $data = json_decode($request->getBody());

        $containerID = $data->Id;

        $this->containerEntity = new ContainerEntity($containerID);
    }

    public function start(): void
    {
        $request = $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/start");
        $this->checkErrorResponse(__FUNCTION__, $request);
    }

    public function kill(): void
    {
        $request = $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/kill");
        $this->checkErrorResponse(__FUNCTION__, $request);
    }

    public function rm(): void
    {
        $request = $this->http->request("DELETE", "containers/" . $this->containerEntity->getEntityID());
        $this->checkErrorResponse(__FUNCTION__, $request);
    }

    public function top(): array
    {
        $request = $this->http->request("GET", "containers/" . $this->containerEntity->getEntityID() . "/top");
        $this->checkErrorResponse(__FUNCTION__, $request);
    }

    public function stop(): void
    {
        $request = $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/stop");
        $this->checkErrorResponse(__FUNCTION__, $request);
    }

    public function pause(): void
    {
        $request = $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/pause");
        $this->checkErrorResponse(__FUNCTION__, $request);
    }

    public function unpause(): void
    {
        $request = $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/unpause");
        $this->checkErrorResponse(__FUNCTION__, $request);
    }
}