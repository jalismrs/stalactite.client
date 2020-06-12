<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\Domain;

use Jalismrs\Stalactite\Client\Access\Domain\Service;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
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
 * ApiAddCustomerRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\Domain
 */
class ApiAddCustomerRelationTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws SerializerException
     * @throws JsonException
     */
    public function testAddCustomerRelation(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestableDomainCustomerRelation(),
                            [
                                AbstractNormalizer::GROUPS => ['main']
                            ]
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->addCustomerRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableCustomer(),
            JwtFactory::create()
        );

        static::assertInstanceOf(DomainCustomerRelation::class, $response->getBody());
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

        $mockService->addCustomerRelation(
            DataTestModelFactory::getTestableDomain()->setUid(null),
            DataTestModelFactory::getTestableCustomer(),
            JwtFactory::create()
        );
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

        $mockService->addCustomerRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableCustomer()->setUid(null),
            JwtFactory::create()
        );
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());

        $mockService->addCustomerRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableCustomer(),
            JwtFactory::create()
        );
    }
}
