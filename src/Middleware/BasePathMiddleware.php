<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class BasePathMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $scriptName = $request->getServerParams()['SCRIPT_NAME'];
        if ($scriptName === '/index.php') {
            return $handler->handle($request);
        }

        $uri = $request->getUri();
        $path = $uri->getPath();

        // Remove the public/index.php part
        $scriptName = dirname($scriptName, 2);
        $length = strlen($scriptName);
        if ($length > 0 && $scriptName !== '/') {
            $path = substr($path, $length);
        }

        // Change the request uri to run the app in a subdirectory.
        $request = $request->withUri($uri->withPath($path));

        return $handler->handle($request);
    }
}
