<?php

namespace App\Http;

use RuntimeException;

final class HttpUnauthorizedException extends RuntimeException implements HttpExceptionInterface
{
    /** @phpstan-ignore-next-line */
    protected $message = 'Unauthorized';

    /** @phpstan-ignore-next-line */
    protected $code = 401;
}
