<?php

namespace App\Exception;

final class HttpMethodNotAllowedException extends HttpException
{
    /** @phpstan-ignore-next-line */
    protected $message = 'Method Not Allowed';

    /** @phpstan-ignore-next-line */
    protected $code = 405;

    /** @var array<string> */
    private array $allowedMethods = [];

    public function setAllowedMethods(array $allowedMethods): void
    {
        $this->allowedMethods = $allowedMethods;
    }

    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}
