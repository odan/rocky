<?php

use App\Middleware\RouterMiddleware;
use App\Routing\Router;
use App\Routing\UrlGenerator;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Relay\Relay;

return [
    // Application settings
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    RequestHandlerInterface::class => function (ContainerInterface $container) {
        $queue = require __DIR__ . '/middleware.php';

        return new Relay(
            $queue,
            function ($entry) use ($container) {
                return $container->get($entry);
            }
        );
    },

    Router::class => function () {
        return new Router(new RouteCollector(new Std(), new GroupCountBased()));
    },

    RouterMiddleware::class => function (ContainerInterface $container) {
        return new RouterMiddleware(
            $container->get(Router::class),
            $container,
            function ($router) {
                (require __DIR__ . '/routes.php')($router);
            },
        );
    },

    UrlGenerator::class => function (ContainerInterface $container) {
        return new UrlGenerator($container->get(Router::class));
    },

    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    ServerRequestCreatorInterface::class => function (ContainerInterface $container) {
        $psr17Factory = $container->get(Psr17Factory::class);

        return new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
    },

    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    StreamFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UploadedFileFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UriFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
];
