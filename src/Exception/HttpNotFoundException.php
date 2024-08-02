<?php

namespace App\Exception;

final class HttpNotFoundException extends HttpException
{
    /** @phpstan-ignore-next-line */
    protected $message = 'Not Found';

    /** @phpstan-ignore-next-line */
    protected $code = 404;
}
