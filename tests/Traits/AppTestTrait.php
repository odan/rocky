<?php

namespace App\Test\Traits;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

trait AppTestTrait
{
    protected ContainerInterface $container;

    protected function setUp(): void
    {
        $this->container = (new ContainerBuilder())
            ->addDefinitions(__DIR__ . '/../../config/container.php')
            ->build();
    }

    protected function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->container->get(RequestHandlerInterface::class)->handle($request);
    }
}
