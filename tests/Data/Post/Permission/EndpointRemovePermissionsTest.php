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

        $testClient = new Client('http://fakeHost');
        $testService = new Service($testClient);

        $testService->removePermissions(ModelFactory::getTestablePost()->setUid(null), [], JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowOnInvalidPermissionList(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::INVALID_MODEL);

        $testClient = new Client('http://fakeHost');
        $testService = new Service($testClient);

        $testService->removePermissions(ModelFactory::getTestablePost(), ['not a permission'], JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodNotCalledOnEmptyPermissionList(): void
    {
        $mockClient = $this->createMockClient(false);
    
        $testService = new Service($mockClient);

        $response = $testService->removePermissions(ModelFactory::getTestablePost(), [], JwtFactory::create());
        
        self::assertNull($response);
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockClient = $this->createMockClient();
        $testService = new Service($mockClient);
        
        $response = $testService->removePermissions(ModelFactory::getTestablePost(), [ModelFactory::getTestablePermission()], JwtFactory::create());
        self::assertInstanceOf(Response::class, $response);
    }
}
