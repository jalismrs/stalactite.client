<?php

namespace Jalismrs\Stalactite\Client\Tests\Authentication\ServerApp;

use Exception;
use Jalismrs\Stalactite\Client\Authentication\Model\ServerApp;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\ApiPaginatedResponseFactory;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class EndpointAllByNameTest
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\ServerApp
 * @covers Jalismrs\Stalactite\Client\Authentication\ServerApp\Service
 */
class EndpointAllByNameTest extends AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws NormalizerException
     * @throws Exception
     * @throws Exception
     */
    public function testRequest(): void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    ApiPaginatedResponseFactory::getFor([
                        Normalizer::getInstance()
                            ->normalize(
                                TestableModelFactory::getTestableServerApp(),
                                [AbstractNormalizer::GROUPS => ['main']]
                            ),
                    ]),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $systemUnderTest = $this->createSystemUnderTest($testClient);

        $response = $systemUnderTest->allByName('name', JwtFactory::create());

        self::assertContainsOnlyInstancesOf(
            ServerApp::class,
            $response->getBody()['results']
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

        $systemUnderTest->allByName('name', JwtFactory::create());
    }
}