<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Domain\Relation;

use Jalismrs\Stalactite\Client\Data\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Data\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;
use Jalismrs\Stalactite\Client\Tests\Data\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class EndpointAllTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Domain\Relation
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Domain\Relation\Service
 */
class EndpointAllTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testGetRelations(): void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'users' => [
                            Normalizer::getInstance()
                                ->normalize(
                                    TestableModelFactory::getTestableDomainUserRelation(),
                                    [
                                        AbstractNormalizer::GROUPS => ['main'],
                                        AbstractNormalizer::IGNORED_ATTRIBUTES => ['domain'],
                                    ]
                                ),
                        ],
                        'customers' => [
                            Normalizer::getInstance()
                                ->normalize(
                                    TestableModelFactory::getTestableDomainCustomerRelation(),
                                    [
                                        AbstractNormalizer::GROUPS => ['main'],
                                        AbstractNormalizer::IGNORED_ATTRIBUTES => ['domain'],
                                    ]
                                ),
                        ],
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $systemUnderTest = $this->createSystemUnderTest($testClient);

        $response = $systemUnderTest->all(
            TestableModelFactory::getTestableDomain(),
            JwtFactory::create()
        );

        static::assertIsArray($response->getBody());

        static::assertArrayHasKey(
            'users',
            $response->getBody()
        );
        static::assertArrayHasKey(
            'customers',
            $response->getBody()
        );

        static::assertContainsOnlyInstancesOf(
            DomainUserRelation::class,
            $response->getBody()['users']
        );
        static::assertContainsOnlyInstancesOf(
            DomainCustomerRelation::class,
            $response->getBody()['customers']
        );
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_DOMAIN_UID);

        $systemUnderTest = $this->createSystemUnderTest();

        $systemUnderTest->all(
            TestableModelFactory::getTestableDomain()
                ->setUid(null),
            JwtFactory::create()
        );
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);

        $systemUnderTest->all(
            TestableModelFactory::getTestableDomain(),
            JwtFactory::create()
        );
    }
}
