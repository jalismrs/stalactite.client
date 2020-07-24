<?php

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User\Relation;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\User\Relation\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

class ApiDeleteAllTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowCustomerLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_USER_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->deleteAll(ModelFactory::getTestableUser()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->deleteAll(ModelFactory::getTestableUser(), JwtFactory::create());
    }
}