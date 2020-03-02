<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp;

use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Authentication\ModelFactory;

/**
 * ApiDeleteTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp
 */
class ApiDeleteTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws SerializerException
     * @throws ServiceException
     */
    public function testThrowMissingUid(): void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionCode(ServiceException::MISSING_TRUSTED_APP_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $trustedApp = ModelFactory::getTestableTrustedApp()->setUid(null);

        $mockService->deleteTrustedApp($trustedApp, 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws SerializerException
     * @throws ServiceException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->deleteTrustedApp(ModelFactory::getTestableTrustedApp(), 'fake user jwt');
    }
}
