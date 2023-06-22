<?php

namespace App\Http;

use RuntimeException;

final class HttpForbiddenException extends RuntimeException implements HttpExceptionInterface
{
    /** @phpstan-ignore-next-line */
    protected $message = 'Forbidden';

    /** @phpstan-ignore-next-line */
    protected $code = 403;
}
