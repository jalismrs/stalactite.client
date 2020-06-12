<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\Customer;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Customer\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;

/**
 * Class ApiUpdateTest
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\Customer
 */
class ApiUpdateTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws SerializerException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_CUSTOMER_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->updateCustomer(ModelFactory::getTestableCustomer()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws SerializerException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->updateCustomer(ModelFactory::getTestableCustomer(), JwtFactory::create());
    }
}
