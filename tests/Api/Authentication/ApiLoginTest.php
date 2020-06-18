<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use JsonException;

/**
 * ApiLoginTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Authentication
 */
class ApiLoginTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws JsonException
     */
    public function testSchemaValidationOnLogin(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    ['jwt' => 'hello'],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        // assert valid return and response content
        $response = $mockService->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );

        self::assertArrayHasKey('jwt', $response->getBody());
        self::assertIsString($response->getBody()['jwt']);
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->login(ModelFactory::getTestableTrustedApp(), 'fakeUserGoogleToken');
    }
}
