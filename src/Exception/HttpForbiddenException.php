<?php

namespace App\Exception;

final class HttpForbiddenException extends HttpException
{
    /** @phpstan-ignore-next-line */
    protected $message = 'Forbidden';

    /** @phpstan-ignore-next-line */
    protected $code = 403;
}
