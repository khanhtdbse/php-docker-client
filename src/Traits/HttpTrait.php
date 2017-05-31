<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 31/05/2017
 * Time: 07:52
 */

namespace DockerClient\Traits;


use GuzzleHttp\Client;

trait HttpTrait
{
    protected $http;

    public function __construct()
    {
        $httpClient = new Client([
            'base_uri' => 'http:/',
            'header' => [
                'Content-Type' => 'application/json'
            ],
            'curl' => [
                CURLOPT_UNIX_SOCKET_PATH => '/var/run/docker.sock'
            ]
        ]);

        $this->http = $httpClient;
    }
}