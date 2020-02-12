<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Authentication\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * LoginTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Authentication
 */
class LoginTest extends
    TestCase
{
    /**
     * testSchemaValidationOnLogin
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws RequestConfigurationException
     * @throws SerializerException
     */
    public function testSchemaValidationOnLogin(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => null,
                        'jwt' => 'hello'
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        // assert valid return and response content
        $response = $mockService->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertIsString($response->getData()['jwt']);
    }
    
    /**
     * testExceptionThrownOnInvalidAPIHost
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws RequestConfigurationException
     * @throws SerializerException
     */
    public function testExceptionThrownOnInvalidAPIHost(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::CLIENT_TRANSPORT);

        $mockClient = new Client('invalidHost');
        $mockService = new Service($mockClient);
        $mockService->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
    }
    
    /**
     * testExceptionThrownOnInvalidAPIResponse
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws RequestConfigurationException
     * @throws SerializerException
     */
    public function testExceptionThrownOnInvalidAPIResponse(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create('invalid API response')
        );

        $mockService->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
    }
    
    /**
     * testExceptionThrownOnInvalidAPIResponseContent
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws RequestConfigurationException
     * @throws SerializerException
     */
    public function testExceptionThrownOnInvalidAPIResponseContent(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => false,
                        'error' => 'An error occurred',
                        'invalidField' => true
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $mockService->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
    }
}
