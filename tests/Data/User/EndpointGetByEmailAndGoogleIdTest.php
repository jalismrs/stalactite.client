<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\User;

use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
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
 * Class EndpointGetByEmailAndGoogleIdTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\User
 *
 * @covers \Jalismrs\Stalactite\Client\Data\User\Service
 */
class EndpointGetByEmailAndGoogleIdTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws ClientException
     * @throws JsonException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testGetByEmailAndGoogleId(): void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            TestableModelFactory::getTestableUser(),
                            [
                                AbstractNormalizer::GROUPS => ['main'],
                            ]
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $systemUnderTest = $this->createSystemUnderTest($testClient);

        $response = $systemUnderTest->getByEmailAndGoogleId(
            'goodmorning@hello.hi',
            '0123456789',
            JwtFactory::create()
        );

        self::assertInstanceOf(
            User::class,
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

        $systemUnderTest->getByEmailAndGoogleId(
            'goodmorning@hello.hi',
            '0123456789',
            JwtFactory::create()
        );
    }

}
