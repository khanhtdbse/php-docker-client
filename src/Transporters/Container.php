<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 11:43
 */

namespace DockerClient\Transporters;

use DockerClient\Collections\ContainerCollection;
use DockerClient\Configs\ContainerConfig;
use DockerClient\Configs\ExecConfig;
use DockerClient\Contracts\EntityContract;
use DockerClient\Contracts\EntityManagerContract;
use DockerClient\Entities\ContainerEntity;
use DockerClient\Entities\ExecEntity;
use DockerClient\Exceptions\Containers\ContainerException;
use DockerClient\Traits\HttpTrait;
use GuzzleHttp\Exception\RequestException;

class Container implements EntityManagerContract
{
    use HttpTrait;

    protected $containerEntity;

    public static function ls(): ContainerCollection
    {
        try {
            $instance = new self();
            $request = $instance->http->request("GET", "containers/json");
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }

        $collection = ContainerCollection::makeFromResponse($request);

        return $collection;
    }

    public function create(ContainerConfig $config): void
    {
        try {
            $request = $this->http->request("POST", "containers/create?name=" . $config->getName(), [
                'json' => $config->getConfigs(),
            ]);
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }

        $data = json_decode($request->getBody());

        $containerID = $data->Id;

        $this->containerEntity = new ContainerEntity($containerID);
    }

    public function inspect(): array
    {
        try {
            $request = $this->http->request("GET", $this->getEntity()->getEntityType() . "/" . $this->getEntity()->getEntityID() . "/json");
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }
        return json_decode($request->getBody()->getContents(), true);
    }

    public function start(): void
    {
        try {
            $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/start");
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }
    }

    public function logs(): string
    {
        try {
            $request = $this->http->request("GET", "containers/" . $this->containerEntity->getEntityID() . "/logs", [
                'query' => [
                    'stdout'     => true,
                    'stderr'     => true,
                    'timestamps' => true,
                ]
            ]);
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }
        return $request->getBody()->getContents();
    }

    public function kill(): void
    {
        try {
            $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/kill");
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }
    }

    public function rm(): void
    {
        try {
            $this->http->request("DELETE", "containers/" . $this->containerEntity->getEntityID());
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }
    }

    public function top(): array
    {
        try {
            $request = $this->http->request("GET", "containers/" . $this->containerEntity->getEntityID() . "/top");
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }
        return json_decode($request->getBody()->getContents(), true);
    }

    public function stop(): void
    {
        try {
            $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/stop");
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }
    }

    public function pause(): void
    {
        try {
            $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/pause");
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }
    }

    public function unpause(): void
    {
        try {
            $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/unpause");
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }
    }

    public function exec(ExecConfig $config): string
    {
        $execEntity = $this->createExec($config);
        return $this->doExec($execEntity);
    }

    public function createExec(ExecConfig $config): ExecEntity
    {
        try {
            $request = $this->http->request("POST", "containers/" . $this->containerEntity->getEntityID() . "/exec", [
                'json' => $config->getConfigs(),
            ]);
        } catch (RequestException $e) {
            throw new ContainerException($e);
        }

        $execID = json_decode($request->getBody()->getContents())->Id;
        return new ExecEntity($execID);
    }

    public function doExec(ExecEntity $execEntity)
    {
        return $execEntity->start();
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

}