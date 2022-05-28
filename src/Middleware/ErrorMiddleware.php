<?php

namespace App\Middleware;

use App\Renderer\ErrorRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class ErrorMiddleware implements MiddlewareInterface
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
        } catch (Throwable $exception) {
            return $this->errorRenderer->render(
                $exception,
                $request,
                StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR
            );
        }
    }
}
