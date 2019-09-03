<?php

namespace jalismrs\Stalactite\Client\Test\Authentication;

use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\Authentication\Client;
use jalismrs\Stalactite\Client\ClientException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class LoginTest extends TestCase
{
    /**
     * @throws ClientException
     * @throws InvalidSchemaException
     */
    public function testSchemaValidationOnLogin(): void
    {
        $response = [
            'success' => true,
            'error' => null,
            'jwt' => 'hello'
        ];

        $response2 = [
            'success' => false,
            'error' => 'An error occurred',
            'jwt' => null
        ];

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode($response)),
            new MockResponse(json_encode($response2))
        ]);

        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        // assert valid return and response content
        $response = $mockAPIClient->login('fakeAppName', 'fakeAppToken', 'fakeUserGoogleToken');
        $this->assertIsArray($response);
        $this->assertTrue($response['success']);
        $this->assertNull($response['error']);
        $this->assertIsString($response['jwt']);

        // assert valid return and response content
        $response = $mockAPIClient->login('fakeAppName', 'fakeAppToken', 'fakeUserGoogleToken');
        $this->assertIsArray($response);
        $this->assertFalse($response['success']);
        $this->assertNotNull($response['error']);
        $this->assertNull($response['jwt']);
    }

    /**
     * @throws ClientException
     * @throws InvalidSchemaException
     */
    public function testExceptionThrownOnInvalidAPIHost(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::CLIENT_TRANSPORT_ERROR);

        $client = new Client('invalidHost');
        $client->login('fakeAppName', 'fakeAppToken', 'fakeUserGoogleToken');
    }

    /**
     * @throws ClientException
     * @throws InvalidSchemaException
     */
    public function testExceptionThrownOnInvalidAPIResponse(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse('invalid API response')
        ]);

        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->login('fakeAppName', 'fakeAppToken', 'fakeUserGoogleToken');
    }

    /**
     * @throws ClientException
     * @throws InvalidSchemaException
     */
    public function testExceptionThrownOnInvalidAPIResponseContent(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $invalidResponse = [
            'success' => false,
            'error' => 'An error occurred',
            'invalidField' => true
        ];

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode($invalidResponse))
        ]);

        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->login('fakeAppName', 'fakeAppToken', 'fakeUserGoogleToken');
    }
}