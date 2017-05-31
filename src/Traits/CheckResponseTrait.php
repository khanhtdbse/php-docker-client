<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 31/05/2017
 * Time: 09:32
 */

namespace DockerClient\Traits;

use GuzzleHttp\Psr7\Response;

trait CheckResponseTrait
{
    protected function checkErrorResponse($method, Response $response)
    {
        if (!isset(self::SUCCESS_STATUS[$method])) {
            throw new \Exception("Can't get success status code in config constant");
        }

        if (!in_array($response->getStatusCode(), self::SUCCESS_STATUS[$method])
            && !in_array($response->getStatusCode(), self::WARNING_STATUS[$method])
        ) {
            $data = json_decode($response->getBody());
            throw new \Exception($data->message);
        };
    }
}