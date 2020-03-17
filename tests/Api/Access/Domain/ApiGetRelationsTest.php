<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\Domain;

use Jalismrs\Stalactite\Client\Access\Domain\Service;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetRelationsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\Domain
 */
class ApiGetRelationsTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws SerializerException
     */
    public function testGetRelations(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode([
                    'users' => [
                        Serializer::getInstance()
                            ->normalize(
                                ModelFactory::getTestableDomainUserRelation(),
                                [
                                    AbstractNormalizer::GROUPS => ['main'],
                                    AbstractNormalizer::IGNORED_ATTRIBUTES => ['domain']
                                ]
                            )
                    ],
                    'customers' => [
                        Serializer::getInstance()
                            ->normalize(
                                ModelFactory::getTestableDomainCustomerRelation(),
                                [
                                    AbstractNormalizer::GROUPS => ['main'],
                                    AbstractNormalizer::IGNORED_ATTRIBUTES => ['domain']
                                ]
                            )
                    ]
                ], JSON_THROW_ON_ERROR)
            )
        );

        $response = $mockService->getRelations(DataTestModelFactory::getTestableDomain(), 'fake user jwt');

        static::assertIsArray($response->getBody());

        static::assertArrayHasKey('users', $response->getBody());
        static::assertArrayHasKey('customers', $response->getBody());

        static::assertContainsOnlyInstancesOf(DomainUserRelation::class, $response->getBody()['users']);
        static::assertContainsOnlyInstancesOf(DomainCustomerRelation::class, $response->getBody()['customers']);
    }

    /**
     * @throws ClientException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(AccessServiceException::class);
        $this->expectExceptionCode(AccessServiceException::MISSING_DOMAIN_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->getRelations(DataTestModelFactory::getTestableDomain()->setUid(null), 'fake user jwt');
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->getRelations(DataTestModelFactory::getTestableDomain(), 'fake user jwt');
    }
}
