<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Token;

use Jalismrs\Stalactite\Client\Authentication\Token\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use JsonException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class ApiLoginTest
 * @package Jalismrs\Stalactite\Client\Tests\Authentication
 */
class EndpointLoginTest extends AbstractTestEndpoint
{
    /**
     * @throws JsonException
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testLogin(): void
    {
        $mockToken = (new Builder())->relatedTo('test-user')->getToken();
        $testClient = new Client('http://fakeHost');
        $testService = new Service($testClient);
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    ['token' => (string)$mockToken],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        // assert valid return and response content
        $response = $testService->login(
            ModelFactory::getTestableClientApp(),
            'fakeUserGoogleToken'
        );

        self::assertArrayHasKey('token', $response->getBody());
        self::assertInstanceOf(Token::class, $response->getBody()['token']);
    }

    /**
     * @throws JsonException
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowOnInvalidTokenReceived(): void
    {
        $this->expectException(AuthenticationServiceException::class);
        $this->expectExceptionCode(AuthenticationServiceException::INVALID_TOKEN);

        $testClient = new Client('http://fakeHost');
        $testService = new Service($testClient);
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    ['token' => 'yolo'],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        // assert valid return and response content
        $testService->login(ModelFactory::getTestableClientApp(), 'fakeUserGoogleToken');
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockClient = $this->createMockClient();
        $testService = new Service($mockClient);
        
        $testService->login(ModelFactory::getTestableClientApp(), 'fakeUserGoogleToken');
    }
}
