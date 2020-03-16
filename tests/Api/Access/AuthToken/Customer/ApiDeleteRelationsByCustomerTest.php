<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\AuthToken\Customer;

use Jalismrs\Stalactite\Client\Access\AuthToken\Customer\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;

/**
 * ApiDeleteRelationsByCustomerTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\AuthToken\Customer
 */
class ApiDeleteRelationsByCustomerTest extends EndpointTest
{
    /**
     * @throws ClientException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(AccessServiceException::class);
        $this->expectExceptionCode(AccessServiceException::MISSING_CUSTOMER_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->deleteRelationsByCustomer(
            ModelFactory::getTestableCustomer()->setUid(null),
            'fake API auth token'
        );
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->deleteRelationsByCustomer(ModelFactory::getTestableCustomer(), 'fake API auth token');
    }
}
