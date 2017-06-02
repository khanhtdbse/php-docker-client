<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 30/05/2017
 * Time: 11:43
 */

namespace DockerClient\Transporters;

use DockerClient\Contracts\EntityContract;
use DockerClient\Contracts\EntityManagerContract;
use DockerClient\Exceptions\Exec\ExecException;
use DockerClient\Traits\HttpTrait;
use GuzzleHttp\Exception\RequestException;

class Exec implements EntityManagerContract
{
    use HttpTrait;

    public $execEntity;


    public function inspect(): array
    {
        try {
            $request = $this->http->request("GET", "exec/" . $this->getEntity()->getEntityID() . "/json");
        } catch (RequestException $e) {
            throw new ExecException($e);
        }

        return json_decode($request->getBody()->getContents(), true);
    }

    public function start(): string
    {
        try {
            $request = $this->http->request("POST", "exec/" . $this->getEntity()->getEntityID() . "/start", [
                'json' => [
                    "Detach" => false,
                    "Tty"    => false
                ]
            ]);
        } catch (RequestException $e) {
            throw new ExecException($e);
        }
        return $this->removeControlChars($request->getBody()->getContents());
    }

    private function removeControlChars($str)
    {
        return substr(preg_replace('/[\x00-\x09\x0B\x0C\x0E\x1E-\x1F\x7F]/', '', $str),1);
    }

    public function setEntity(EntityContract $entity)
    {
        $this->execEntity = $entity;
    }

    public function getEntity(): EntityContract
    {
        return $this->execEntity;
    }
}