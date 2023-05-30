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

    public function testHome(): void
    {
        $request = new ServerRequest('GET', '/');
        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('/users/daniel/123', (string)$response->getBody());
    }

    public function testUsername(): void
    {
        $request = new ServerRequest('GET', '/users/max/9876');
        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('max', (string)$response->getBody());
        $this->assertStringContainsString('9876', (string)$response->getBody());
    }

    public function testPageNotFound(): void
    {
        $request = new ServerRequest('GET', '/nada');
        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
