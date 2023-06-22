<?php

namespace App\Middleware;

use App\Http\HttpMethodNotAllowedException;
use App\Http\HttpUnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Auth request object
        $token = $request->getHeaderLine('Authorization');

        // Check the token here
        // ...

        if (!$token) {
            // No valid authentication credentials for the requested resource
            throw new HttpUnauthorizedException();
        }

        // Invoke next middleware
        return $handler->handle($request);
    }
}
