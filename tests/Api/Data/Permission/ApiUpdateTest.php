<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\Permission;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Permission\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;

/**
 * Class ApiUpdateTest
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\Post
 */
class ApiUpdateTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws NormalizerException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_PERMISSION_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->updatePermission(ModelFactory::getTestablePermission()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws NormalizerException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->updatePermission(ModelFactory::getTestablePermission(), JwtFactory::create());
    }
}