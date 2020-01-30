<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Authentication\Service;
use Jalismrs\Stalactite\Client\ClientException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * LoginTest
 *
 * @packageJalismrs\Stalactite\Service\Tests\Authentication
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
     */
    public function testSchemaValidationOnLogin(): void
    {
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'jwt' => 'hello'
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    ),
                    new MockResponse(
                        json_encode(
                            [
                                'success' => false,
                                'error' => 'An error occurred',
                                'jwt' => null
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
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

        // assert valid return and response content
        $response = $mockService->login(
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
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testExceptionThrownOnInvalidAPIHost(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::CLIENT_TRANSPORT);
    
        $mockService = new Service('invalidHost');
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
     */
    public function testExceptionThrownOnInvalidAPIResponse(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse('invalid API response')
                ]
            )
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
     */
    public function testExceptionThrownOnInvalidAPIResponseContent(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => false,
                                'error' => 'An error occurred',
                                'invalidField' => true
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $mockService->login(
            ModelFactory::getTestableTrustedApp(),
            'fakeUserGoogleToken'
        );
    }
}
