<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiCreateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp
 */
class ApiCreateTest extends EndpointTest
{
    /**
     * @throws SerializerException
     * @throws ClientException
     * @throws JsonException
     */
    public function testCreate(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestableTrustedApp(),
                            [
                                AbstractNormalizer::GROUPS => [
                                    'main',
                                    'reset',
                                ]
                            ]
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->createTrustedApp(ModelFactory::getTestableTrustedApp()->setUid(null), JwtFactory::create());

        self::assertInstanceOf(TrustedApp::class, $response->getBody());
    }

    /**
     * @throws ClientException
     * @throws SerializerException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->createTrustedApp(ModelFactory::getTestableTrustedApp(), JwtFactory::create());
    }
}
