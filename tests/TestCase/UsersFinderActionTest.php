<?php

namespace App\Test\TestCase;

use App\Test\Traits\AppTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\UsersFinderAction
 */
class UsersFinderActionTest extends TestCase
{
    use AppTestTrait;

    public function test(): void
    {
        $request = new ServerRequest('GET', '/api/users');
        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertSame('[{"username":"john","email":"john@example.com"}]', (string)$response->getBody());
    }

    public function test2(): void
    {
        $request = new ServerRequest('GET', '/api/pizzas');
        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }
}
