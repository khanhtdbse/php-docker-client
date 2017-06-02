<?php
/**
 * Created by PhpStorm.
 * User: tran.duy.khanh
 * Date: 01/06/2017
 * Time: 14:45
 */

namespace DockerClient\Exceptions\Volumes;

use GuzzleHttp\Exception\RequestException;
use Throwable;

class VolumeException extends \Exception
{

    public function __construct(RequestException $e, $entity = null, $code = 0, Throwable $previous = null)
    {
        $statusCode = $e->getResponse()->getStatusCode();
        $exceptionName = "\DockerClient\Exceptions\Volumes\Volume" . $statusCode . "Exception";
        $message = json_decode($e->getResponse()->getBody()->getContents())->message;
        throw new $exceptionName($message, $code);
    }

}