<?php
declare(strict_types = 1);

namespace Test\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Client;
use Jalismrs\Stalactite\Client\ClientException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * LoginTest
 *
 * @package Test\Authentication
 */
class LoginTest extends
    TestCase
{
    /**
     * testSchemaValidationOnLogin
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testSchemaValidationOnLogin() : void
    {
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error'   => null,
                                'jwt'     => 'hello'
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    ),
                    new MockResponse(
                        json_encode(
                            [
                                'success' => false,
                                'error'   => 'An error occurred',
                                'jwt'     => null
                            ],
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
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertIsString($response->getData()['jwt']);
        
        // assert valid return and response content
        $response = $mockAPIClient->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
        self::assertFalse($response->isSuccess());
        self::assertNotNull($response->getError());
        self::assertNull($response->getData()['jwt']);
    }
    
    /**
     * testExceptionThrownOnInvalidAPIHost
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
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
     * testExceptionThrownOnInvalidAPIResponse
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
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
     * testExceptionThrownOnInvalidAPIResponseContent
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testExceptionThrownOnInvalidAPIResponseContent() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'      => false,
                                'error'        => 'An error occurred',
                                'invalidField' => true
                            ],
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
