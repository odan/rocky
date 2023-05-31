<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class OutgoingMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // Handle outgoing response
        // For example: Header Propagation, CORS, Compression

        // Add some response headers
        $rand = rand(1, 9999999);
        $response = $response->withAddedHeader('X-OutgoingMiddleware-' . $rand, (string)$rand);
        $response = $response->withHeader('X-OutgoingMiddleware', 'value');

        return $response;
    }
}
