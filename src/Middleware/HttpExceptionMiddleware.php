<?php

namespace App\Middleware;

use App\Exception\HttpExceptionInterface;
use App\Renderer\ErrorRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HttpExceptionMiddleware implements MiddlewareInterface
{
    private ErrorRenderer $errorRenderer;

    public function __construct(ErrorRenderer $renderer)
    {
        $this->errorRenderer = $renderer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpExceptionInterface $exception) {
            return $this->errorRenderer->render($exception, $request, $exception->getCode());
        }
    }
}
