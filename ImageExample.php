<?php
require "vendor/autoload.php";
use DockerClient\Container;
use DockerClient\ContainerConfig;
use DockerClient\ImageConfig;
use DockerClient\Image;

$image = new Image();

$imageConfig = new ImageConfig('test.tar.bz2', [
    't' => 'myimglol'
]);

$image->build($imageConfig);

$container = new Container();

$containerConfig = new ContainerConfig(
    $containerName = 'huhu',
    $configs = [
        'Image' => $image->getEntity()
    ]
);

$container->create($containerConfig);

$container->start();


