<?php

namespace App\Http;

use RuntimeException;

final class HttpNotFoundException extends RuntimeException implements HttpExceptionInterface
{
    /** @phpstan-ignore-next-line */
    protected $message = 'Not Found';

    /** @phpstan-ignore-next-line */
    protected $code = 404;
}
