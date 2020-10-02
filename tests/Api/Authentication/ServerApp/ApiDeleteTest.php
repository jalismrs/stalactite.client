<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication\ServerApp;

use Jalismrs\Stalactite\Client\Authentication\ServerApp\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * ApiDeleteTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp
 */
class ApiDeleteTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowMissingUid(): void
    {
        $this->expectException(AuthenticationServiceException::class);
        $this->expectExceptionCode(AuthenticationServiceException::MISSING_SERVER_APP_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->delete(ModelFactory::getTestableServerApp()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws AuthenticationServiceException
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->delete(ModelFactory::getTestableServerApp(), JwtFactory::create());
    }
}
