<?php

namespace App\Test\TestCase;

use App\Test\Traits\AppTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\UsersReaderAction
 */
class UsersReaderActionTest extends TestCase
{
    use AppTestTrait;

    public function testAction(): void
    {
        $request = new ServerRequest('GET', '/api/users/daniel/123');
        $request = $request->withHeader('Authorization', 'token');

        $response = $this->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertStringContainsString('/api/users/daniel/123', (string)$response->getBody());
    }
}
