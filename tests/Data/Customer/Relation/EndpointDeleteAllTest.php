<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer\Relation;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Customer\Relation\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

class EndpointDeleteAllTest extends AbstractTestEndpoint
{
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowCustomerLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_CUSTOMER_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->deleteAll(ModelFactory::getTestableCustomer()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->deleteAll(ModelFactory::getTestableCustomer(), JwtFactory::create());
    }
}
