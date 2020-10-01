<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication\Token;

use Jalismrs\Stalactite\Client\Authentication\Token\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use JsonException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class ApiLoginTest
 * @package Jalismrs\Stalactite\Client\Tests\Api\Authentication
 */
class ApiLoginTest extends EndpointTest
{
    /**
     * @throws JsonException
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testLogin(): void
    {
        $mockToken = (new Builder())->relatedTo('test-user')->getToken();
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    ['token' => (string)$mockToken],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        // assert valid return and response content
        $response = $mockService->login(
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

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    ['token' => 'yolo'],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        // assert valid return and response content
        $mockService->login(ModelFactory::getTestableClientApp(), 'fakeUserGoogleToken');
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->login(ModelFactory::getTestableClientApp(), 'fakeUserGoogleToken');
    }
}
