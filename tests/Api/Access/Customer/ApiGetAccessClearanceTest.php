<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\Customer;

use Jalismrs\Stalactite\Client\Access\Customer\Service;
use Jalismrs\Stalactite\Client\Access\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetAccessClearanceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\Customer
 */
class ApiGetAccessClearanceTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     */
    public function testGetAccessClearance(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestableAccessClearance(),
                            [
                                AbstractNormalizer::GROUPS => ['main']
                            ]
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->getAccessClearance(
            DataTestModelFactory::getTestableCustomer(),
            DataTestModelFactory::getTestableDomain(),
            JwtFactory::create()
        );

        self::assertInstanceOf(AccessClearance::class, $response->getBody());
    }

    /**
     * @throws ClientException
     */
    public function testThrowCustomerLacksUid(): void
    {
        $this->expectException(AccessServiceException::class);
        $this->expectExceptionCode(AccessServiceException::MISSING_CUSTOMER_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->getAccessClearance(
            DataTestModelFactory::getTestableCustomer()->setUid(null),
            DataTestModelFactory::getTestableDomain(),
            JwtFactory::create()
        );
    }

    /**
     * @throws ClientException
     */
    public function testThrowDomainLacksUid(): void
    {
        $this->expectException(AccessServiceException::class);
        $this->expectExceptionCode(AccessServiceException::MISSING_DOMAIN_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->getAccessClearance(
            DataTestModelFactory::getTestableCustomer(),
            DataTestModelFactory::getTestableDomain()->setUid(null),
            JwtFactory::create()
        );
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());

        $mockService->getAccessClearance(
            DataTestModelFactory::getTestableCustomer(),
            DataTestModelFactory::getTestableDomain(),
            JwtFactory::create()
        );
    }
}
