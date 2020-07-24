<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\Domain\Relation;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Domain\Relation\Service;
use Jalismrs\Stalactite\Client\Data\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Data\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ApiGetRelationsTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testGetRelations(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode([
                    'users' => [
                        Normalizer::getInstance()
                            ->normalize(
                                ModelFactory::getTestableDomainUserRelation(),
                                [
                                    AbstractNormalizer::GROUPS => ['main'],
                                    AbstractNormalizer::IGNORED_ATTRIBUTES => ['domain']
                                ]
                            )
                    ],
                    'customers' => [
                        Normalizer::getInstance()
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

        $response = $mockService->all(ModelFactory::getTestableDomain(), JwtFactory::create());

        static::assertIsArray($response->getBody());

        static::assertArrayHasKey('users', $response->getBody());
        static::assertArrayHasKey('customers', $response->getBody());

        static::assertContainsOnlyInstancesOf(DomainUserRelation::class, $response->getBody()['users']);
        static::assertContainsOnlyInstancesOf(DomainCustomerRelation::class, $response->getBody()['customers']);
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_DOMAIN_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->all(ModelFactory::getTestableDomain()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->all(ModelFactory::getTestableDomain(), JwtFactory::create());
    }
}
