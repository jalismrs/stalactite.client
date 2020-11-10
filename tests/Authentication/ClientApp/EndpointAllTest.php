<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\ClientApp;

use Exception;
use Jalismrs\Stalactite\Client\Authentication\Model\ClientApp;
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
 * Class EndpointAllTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\ClientApp
 *
 * @covers \Jalismrs\Stalactite\Client\Authentication\ClientApp\Service
 */
class EndpointAllTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws NormalizerException
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
                                TestableModelFactory::getTestableClientApp(),
                                [AbstractNormalizer::GROUPS => ['main']]
                            ),
                    ]),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $systemUnderTest = $this->createSystemUnderTest($testClient);

        $response = $systemUnderTest->all(JwtFactory::create());

        self::checkPaginatedResponse($response, ClientApp::class);
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);

        $systemUnderTest->all(JwtFactory::create());
    }
}
