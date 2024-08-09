<?php

namespace App\Middleware;

use App\Routing\Router;
use App\Routing\RoutingResults;
use FastRoute\Dispatcher\GroupCountBased;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

final class RoutingMiddleware implements MiddlewareInterface
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Dispatch
        $dispatcher = new GroupCountBased($this->router->getRouteCollector()->getData());

        $httpMethod = $request->getMethod();
        $uri = $request->getUri()->getPath();
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        $routeStatus = (int)$routeInfo[0];
        $routingResults = null;

        if ($routeStatus === RoutingResults::FOUND) {
            $routingResults = new RoutingResults(
                $routeStatus,
                $routeInfo[1],
                $request->getMethod(),
                $uri,
                $routeInfo[2]
            );
        }

        if ($routeStatus === RoutingResults::METHOD_NOT_ALLOWED) {
            $routingResults = new RoutingResults(
                $routeStatus,
                null,
                $request->getMethod(),
                $uri,
                $routeInfo[1],
            );
        }

        if ($routeStatus === RoutingResults::NOT_FOUND) {
            $routingResults = new RoutingResults($routeStatus, null, $request->getMethod(), $uri);
        }

        if (!$routingResults) {
            throw new RuntimeException('An unexpected error occurred while performing routing.');
        }

        $request = $request->withAttribute(RoutingResults::class, $routingResults);

        return $handler->handle($request);
    }
}
