<?php

namespace App\Http;

use RuntimeException;

final class HttpNotAllowedException extends RuntimeException implements HttpExceptionInterface
{
    /** @phpstan-ignore-next-line */
    protected $message = 'Method Not Allowed';

    /** @phpstan-ignore-next-line */
    protected $code = 405;
}
