<?php

namespace App\Http;

final class HttpUnauthorizedException extends HttpException
{
    /** @phpstan-ignore-next-line */
    protected $message = 'Unauthorized';

    /** @phpstan-ignore-next-line */
    protected $code = 401;
}
