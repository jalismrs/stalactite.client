<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp;

use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;

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
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(AuthenticationServiceException::class);
        $this->expectExceptionCode(AuthenticationServiceException::MISSING_TRUSTED_APP_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->updateTrustedApp(ModelFactory::getTestableTrustedApp()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws AuthenticationServiceException
     * @throws ClientException
     * @throws NormalizerException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->updateTrustedApp(ModelFactory::getTestableTrustedApp(), JwtFactory::create());
    }
}
