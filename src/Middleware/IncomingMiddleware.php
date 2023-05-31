<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class IncomingMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Check incoming request object
        // For example: Logging, Auth, Redirection, HTTP Caching, Session, Localization etc.
        // ...

        // Invoke next middleware
        return $handler->handle($request);
    }
}
