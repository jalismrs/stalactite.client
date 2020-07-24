<?php

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\Relation;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Relation\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

class ApiDeleteDomainCustomerRelationTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_DOMAIN_CUSTOMER_RELATION_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->deleteDomainCustomerRelation(ModelFactory::getTestableDomainCustomerRelation()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->deleteDomainCustomerRelation(ModelFactory::getTestableDomainCustomerRelation(), JwtFactory::create());
    }
}