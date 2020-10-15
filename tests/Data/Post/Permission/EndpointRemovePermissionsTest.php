<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Post\Permission;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Post\Permission\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Util\Response;
use Psr\SimpleCache\InvalidArgumentException;

class EndpointRemovePermissionsTest extends AbstractTestEndpoint
{
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_POST_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->removePermissions(ModelFactory::getTestablePost()->setUid(null), [], JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowOnInvalidPermissionList(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::INVALID_MODEL);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->removePermissions(ModelFactory::getTestablePost(), ['not a permission'], JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodNotCalledOnEmptyPermissionList(): void
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects(static::never())
            ->method('request');

        $mockService = new Service($mockClient);

        $response = $mockService->removePermissions(ModelFactory::getTestablePost(), [], JwtFactory::create());
        self::assertNull($response);
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $response = $mockService->removePermissions(ModelFactory::getTestablePost(), [ModelFactory::getTestablePermission()], JwtFactory::create());
        self::assertInstanceOf(Response::class, $response);
    }
}
