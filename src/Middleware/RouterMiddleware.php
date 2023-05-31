<?php

namespace App\Middleware;

use App\Http\HttpNotAllowedException;
use App\Http\HttpNotFoundException;
use App\Routing\Router;
use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Relay\Relay;

final class RouterMiddleware implements MiddlewareInterface
{
    private Router $router;
    private ContainerInterface $container;

    public function __construct(Router $router, ContainerInterface $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Collect routes
        (require __DIR__ . '/../../config/routes.php')($this->router);

        $dispatcher = new GroupCountBased($this->router->getRouteCollector()->getData());

        $httpMethod = $request->getMethod();
        $uri = $request->getUri()->getPath();
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        $dispatcherResult = $routeInfo[0];

        if ($dispatcherResult === Dispatcher::FOUND) {
            return $this->handleFound($request, $handler, $routeInfo);
        }

        if ($dispatcherResult === Dispatcher::METHOD_NOT_ALLOWED) {
            // 405 Method Not Allowed
            throw new HttpNotAllowedException();
        }

        // 404 Not Found
        throw new HttpNotFoundException();
    }

    private function handleFound(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
        array $routeInfo
    ): ResponseInterface {
        $routeHandler = $routeInfo[1];
        $vars = $routeInfo[2];

        $response = $handler->handle($request);

        // Get handler and middlewares
        $routeMatch = $routeHandler();

        if (!empty($routeMatch['middleware'])) {
            $response = $this->invokeMiddlewareHandlers($request, $response, $routeMatch['middleware']);
        }

        /** @var callable $actionHandler */
        $actionHandler = $routeMatch['handler'];

        if (is_string($actionHandler)) {
            /** @var callable $actionHandler */
            $actionHandler = $this->container->get($actionHandler);
        }

        return $actionHandler($request, $response, $vars);
    }

    private function invokeMiddlewareHandlers(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $middlewares
    ): ResponseInterface {
        // Tunnel the response object through the route/group specific middleware stack
        $middlewares[] = new class($response) implements MiddlewareInterface {
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
