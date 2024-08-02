<?php

namespace App\Exception;

use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Throwable;

abstract class HttpException extends RuntimeException implements HttpExceptionInterface
{
    private RequestInterface $request;

    public function __construct(
        RequestInterface $request,
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->request = $request;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
