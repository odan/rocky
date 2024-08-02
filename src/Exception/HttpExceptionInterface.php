<?php

namespace App\Exception;

use Psr\Http\Message\RequestInterface;

interface HttpExceptionInterface
{
    public function getRequest(): RequestInterface;
}
