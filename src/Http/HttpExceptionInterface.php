<?php

namespace App\Http;

use Psr\Http\Message\RequestInterface;

interface HttpExceptionInterface
{
    public function getRequest(): RequestInterface;
}
