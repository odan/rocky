<?php

namespace App\Http;

final class HttpMethodNotAllowedException extends HttpException
{
    /** @phpstan-ignore-next-line */
    protected $message = 'Method Not Allowed';

    /** @phpstan-ignore-next-line */
    protected $code = 405;
}
