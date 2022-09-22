<?php

namespace App\Middleware;

use App\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class BasePathMiddleware implements MiddlewareInterface
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $basePath = $this->getBasePath($request->getServerParams());
        $this->router->setBasePath($basePath);

        return $handler->handle($request);
    }

    /**
     * Return basePath for apache server.
     *
     * @param array $server The SERVER data to use
     *
     * @return string The base path
     */
    private function getBasePath(array $server): string
    {
        if (!isset($server['REQUEST_URI'])) {
            return '';
        }

        $scriptName = $server['SCRIPT_NAME'];

        $basePath = (string)parse_url($server['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = str_replace('\\', '/', dirname($scriptName, 2));

        if ($scriptName === '/') {
            return '';
        }

        $length = strlen($scriptName);
        if ($length > 0) {
            $basePath = substr($basePath, 0, $length);
        }

        if (strlen($basePath) > 1) {
            return $basePath;
        }

        return '';
    }
}
