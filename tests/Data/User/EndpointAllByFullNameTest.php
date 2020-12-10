<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\User;

use Exception;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\ApiPaginatedResponseFactory;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;
use Jalismrs\Stalactite\Client\Tests\Data\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class EndpointAllByFullNameTest
 * @package Jalismrs\Stalactite\Client\Tests\Data\User
 * @covers \Jalismrs\Stalactite\Client\Data\User\Service
 */
class EndpointAllByFullNameTest extends AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testRequest(): void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    ApiPaginatedResponseFactory::getFor([Normalizer::getInstance()
                        ->normalize(
                            TestableModelFactory::getTestableUser(),
                            [
                                AbstractNormalizer::GROUPS => ['min'],
                            ]
                        ),
                    ]),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $systemUnderTest = $this->createSystemUnderTest($testClient);

        $response = $systemUnderTest->allByFullName('fullName', JwtFactory::create());

        self::checkPaginatedResponse($response, User::class);
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);

        $systemUnderTest->allByFullName('fullName', JwtFactory::create());
    }
}