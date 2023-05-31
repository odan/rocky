<?php

namespace App\Test\TestCase;

use App\Test\Traits\AppTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\HomeAction
 */
class HomeActionTest extends TestCase
{
    use AppTestTrait;

    public function testAction(): void
    {
        $request = new ServerRequest('GET', '/');
        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertSame('Hello World', (string)$response->getBody());
    }

    public function testPageNotFound(): void
    {
        $request = new ServerRequest('GET', '/nada');
        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
