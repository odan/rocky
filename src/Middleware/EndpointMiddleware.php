<?php

namespace App\Middleware;

use App\Http\HttpMethodNotAllowedException;
use App\Http\HttpNotFoundException;
use App\Routing\RoutingResults;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Relay\Relay;
use RuntimeException;

/**
 * Routing results to endpoint dispatcher middleware.
 */
class EndpointMiddleware implements MiddlewareInterface
{
    private ContainerInterface $container;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ContainerInterface $container, ResponseFactoryInterface $responseFactory)
    {
        $this->container = $container;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /* @var RoutingResults $routingResults */
        $routingResults = $request->getAttribute(RoutingResults::class);

        if (!$routingResults instanceof RoutingResults) {
            throw new RuntimeException('An unexpected error occurred while handling routing results.');
        }

        $routeStatus = $routingResults->getRouteStatus();
        if ($routeStatus === RoutingResults::FOUND) {
            return $this->handleFound($request, $handler, $routingResults);
        }

        if ($routeStatus === RoutingResults::NOT_FOUND) {
            // 404 Not Found
            throw new HttpNotFoundException($request);
        }

        if ($routeStatus === RoutingResults::METHOD_NOT_ALLOWED) {
            // 405 Method Not Allowed
            $exception = new HttpMethodNotAllowedException($request);
            $exception->setAllowedMethods($routingResults->getAllowedMethods());

            throw $exception;
        }

        throw new RuntimeException('An unexpected error occurred while endpoint handling.');
    }

    private function handleFound(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
        RoutingResults $routingResults
    ): ResponseInterface {
        $route = $routingResults->getRoute() ?? throw new RuntimeException('Route not found.');
        $vars = $routingResults->getRouteArguments();

        $response = $this->responseFactory->createResponse();

        // Get handler and middlewares
        $actionHandler = $route->getHandler();
        $middlewares = $route->getMiddlewareStack();

        // Endpoint and group specific middleware
        if ($middlewares) {
            $response = $this->invokeMiddlewareStack($request, $response, $middlewares);
        }

        if (is_string($actionHandler)) {
            /** @var callable $actionHandler */
            $actionHandler = $this->container->get($actionHandler);
        }

        return $actionHandler($request, $response, $vars);
    }

    private function invokeMiddlewareStack(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $middlewares
    ): ResponseInterface {
        // Tunnel the response object through the route/group specific middleware stack
        $middlewares[] = new class ($response) implements MiddlewareInterface {
            private ResponseInterface $response;

            public function __construct(ResponseInterface $response)
            {
                $this->response = $response;
            }

            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                return $this->response;
            }
        };

        $runner = new Relay(
            $middlewares,
            function ($entry) {
                if (is_string($entry)) {
                    return $this->container->get($entry);
                }

                return $entry;
            }
        );

        return $runner->handle($request);
    }
}
