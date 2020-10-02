<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication\ClientApp;

use Jalismrs\Stalactite\Client\Authentication\ClientApp\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * ApiUpdateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp
 */
class ApiUpdateTest extends EndpointTest
{
    /**
     * @throws AuthenticationServiceException
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(AuthenticationServiceException::class);
        $this->expectExceptionCode(AuthenticationServiceException::MISSING_CLIENT_APP_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->update(ModelFactory::getTestableClientApp()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws AuthenticationServiceException
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->update(ModelFactory::getTestableClientApp(), JwtFactory::create());
    }
}
