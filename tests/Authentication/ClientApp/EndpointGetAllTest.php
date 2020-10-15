<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\ClientApp;

use Jalismrs\Stalactite\Client\Authentication\ClientApp\Service;
use Jalismrs\Stalactite\Client\Authentication\Model\ClientApp;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetAllTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\TrustedApp
 */
class EndpointGetAllTest extends AbstractTestEndpoint
{
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testGetAll(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        Normalizer::getInstance()
                            ->normalize(
                                ModelFactory::getTestableClientApp(),
                                [AbstractNormalizer::GROUPS => ['main']]
                            )
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->all(JwtFactory::create());

        self::assertContainsOnlyInstancesOf(ClientApp::class, $response->getBody());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->all(JwtFactory::create());
    }
}
