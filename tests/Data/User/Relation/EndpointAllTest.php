<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\User\Relation;

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
 * @package Jalismrs\Stalactite\Client\Tests\Data\User\Relation
 *
 * @covers \Jalismrs\Stalactite\Client\Data\User\Relation\Service
 */
class EndpointAllTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     * @throws JsonException
     */
    public function testGetRelations(): void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        Normalizer::getInstance()
                            ->normalize(
                                TestableModelFactory::getTestableDomainUserRelation(),
                                [
                                    AbstractNormalizer::GROUPS => ['main'],
                                    AbstractNormalizer::IGNORED_ATTRIBUTES => ['customer'],
                                ]
                            ),
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $systemUnderTest = $this->createSystemUnderTest($testClient);

        $response = $systemUnderTest->all(
            TestableModelFactory::getTestableUser(),
            JwtFactory::create()
        );

        self::assertContainsOnlyInstancesOf(
            DomainUserRelation::class,
            $response->getBody()
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
            TestableModelFactory::getTestableUser(),
            JwtFactory::create()
        );
    }

    /**
     * testThrowLacksUid
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_USER_UID);

        $systemUnderTest = $this->createSystemUnderTest();

        $systemUnderTest->all(
            TestableModelFactory::getTestableUser()
                ->setUid(null),
            JwtFactory::create()
        );
    }

}
