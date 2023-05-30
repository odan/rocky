<?php

namespace App\Test\TestCase;

use App\Test\Traits\AppTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\UserListAction
 */
class UserListActionTest extends TestCase
{
    use AppTestTrait;

    public function test1(): void
    {
        $request = new ServerRequest('GET', '/api');
        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('Show users', (string)$response->getBody());
        $this->assertSame('value', $response->getHeaderLine('X-ApiExceptionMiddleware'));
    }

    public function test2(): void
    {
        $request = new ServerRequest('GET', '/api/users');
        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('Show users', (string)$response->getBody());
    }

    public function test3(): void
    {
        $request = new ServerRequest('GET', '/api/pizzas');
        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('Show users', (string)$response->getBody());
    }
}
