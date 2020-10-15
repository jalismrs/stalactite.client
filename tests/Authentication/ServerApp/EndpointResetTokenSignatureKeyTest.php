<?php

namespace Jalismrs\Stalactite\Client\Tests\Authentication\ServerApp;

use Jalismrs\Stalactite\Client\Authentication\Model\ServerApp;
use Jalismrs\Stalactite\Client\Authentication\ServerApp\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class EndpointResetTokenSignatureKeyTest extends AbstractTestEndpoint
{
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     * @throws JsonException
     */
    public function testResetTokenSignatureKey(): void
    {
        $serverApp = ModelFactory::getTestableServerApp();
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            $serverApp,
                            [AbstractNormalizer::GROUPS => ['main']]
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->resetTokenSignatureKey($serverApp, JwtFactory::create());

        self::assertInstanceOf(ServerApp::class, $response->getBody());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(AuthenticationServiceException::class);
        $this->expectExceptionCode(AuthenticationServiceException::MISSING_SERVER_APP_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->resetTokenSignatureKey(
            ModelFactory::getTestableServerApp()->setUid(null),
            JwtFactory::create()
        );
    }

    /**
     * @throws AuthenticationServiceException
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->resetTokenSignatureKey(ModelFactory::getTestableServerApp(), JwtFactory::create());
    }
}
