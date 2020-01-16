<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\Authentication;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Authentication\Client;
use Jalismrs\Stalactite\Client\ClientException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * LoginTest
 *
 * @package Jalismrs\Stalactite\Test\Authentication
 */
class LoginTest extends
    TestCase
{
    /**
     * testSchemaValidationOnLogin
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testSchemaValidationOnLogin() : void
    {
        $response = [
            'success' => true,
            'error'   => null,
            'jwt'     => 'hello'
        ];
        
        $response2 = [
            'success' => false,
            'error'   => 'An error occurred',
            'jwt'     => null
        ];
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            $response,
                            JSON_THROW_ON_ERROR
                        )
                    ),
                    new MockResponse(
                        json_encode(
                            $response2,
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        // assert valid return and response content
        $response = $mockAPIClient->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
        self::assertIsString($response->getData()['jwt']);
        
        // assert valid return and response content
        $response = $mockAPIClient->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
        self::assertFalse($response->success());
        self::assertNotNull($response->getError());
        self::assertNull($response->getData()['jwt']);
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testExceptionThrownOnInvalidAPIHost() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::CLIENT_TRANSPORT_ERROR);
        
        $client = new Client('invalidHost');
        $client->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testExceptionThrownOnInvalidAPIResponse() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse('invalid API response')
                ]
            )
        );
        
        $mockAPIClient->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidSchemaException
     * @throws InvalidDataTypeException
     */
    public function testExceptionThrownOnInvalidAPIResponseContent() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $invalidResponse = [
            'success'      => false,
            'error'        => 'An error occurred',
            'invalidField' => true
        ];
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            $invalidResponse,
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $mockAPIClient->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
    }
}
