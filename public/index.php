<?php

require __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Psr\Http\Server\RequestHandlerInterface;

$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/../config/container.php')
    ->build();

/** @var ServerRequestCreatorInterface $serverRequestCreator */
$serverRequestCreator = $container->get(ServerRequestCreatorInterface::class);

$response = $container->get(RequestHandlerInterface::class)->handle(
    $serverRequestCreator->fromGlobals()
);

(new SapiEmitter())->emit($response);
