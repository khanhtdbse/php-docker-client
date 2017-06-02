<?php
use DockerClient\Transporters\Container;
use DockerClient\Configs\ContainerConfig;
use DockerClient\Entities\ContainerEntity;
use DockerClient\Transporters\Image;
use DockerClient\Configs\ImageConfig;

require "vendor/autoload.php";

$testContainerName = str_random(10);
$testImageName = strtolower(str_random(10));

switch ($argv[1]) {
    // List all containers
    case 'ls':
        dd(Container::ls());
        break;

    // Create and start a new container
    case 'create':
        $containerConfig = new ContainerConfig($testContainerName, [
            'Image' => 'nginx'
        ]);
        $container = new Container();
        $container->create($containerConfig);
        // Start
        $container->start();
        echo $container->getEntity()->getEntityID();
        break;


    case 'build':
        $image = new Image();
        $imageConfig = new ImageConfig('compressWithDockerfile.tar.bz2', [
            't' => $testImageName
        ]);
        $image->build($imageConfig);

        $container = new Container();

        $containerConfig = new ContainerConfig(
            $containerName = $testContainerName,
            $configs = [
                'Image' => $image->getEntity()
            ]
        );
        $container->create($containerConfig);
        $container->start();

        echo $container->getEntity()->getEntityID();
        break;

    // Start a stopped container
    case 'start':
        if (!isset($argv[2])){
            die("Please provide a containerID or containerName to start! \n");
        }
        $containerEntity = new ContainerEntity($argv[2]);
        $containerEntity->start();

        echo $containerEntity->getEntityID();
        break;

    // Stop a container
    case 'stop':
        if (!isset($argv[2])){
            die("Please provide a containerID or containerName to stop! \n");
        }
        $containerEntity = new ContainerEntity($argv[2]);
        $containerEntity->stop();

        echo $containerEntity->getEntityID();
        break;


    case 'test':
        fwrite(STDOUT, "OK");
        break;
}








