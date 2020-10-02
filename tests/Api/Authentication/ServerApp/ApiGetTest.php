<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication\ServerApp;

use Jalismrs\Stalactite\Client\Authentication\Model\ServerApp;
use Jalismrs\Stalactite\Client\Authentication\ServerApp\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp
 */
class ApiGetTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws NormalizerException
     */
    public function testGet(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestableServerApp(),
                            [AbstractNormalizer::GROUPS => ['main']]
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->get(ModelFactory::getTestableServerApp()->getUid(), JwtFactory::create());

        self::assertInstanceOf(ServerApp::class, $response->getBody());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->get(ModelFactory::getTestableServerApp()->getUid(), JwtFactory::create());
    }
}
