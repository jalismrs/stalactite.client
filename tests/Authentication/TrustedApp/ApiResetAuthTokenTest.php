<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\Authentication\TrustedApp;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\Authentication\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiResetAuthTokenTest
 *
 * @package Jalismrs\Stalactite\Test\Authentication\TrustedApp
 */
class ApiResetAuthTokenTest extends
    TestCase
{
    /**
     * testResetTrustedAppAuthToken
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testResetAuthToken() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'    => true,
                            'error'      => null,
                            'trustedApp' => ModelFactory::getTestableTrustedApp()
                                                        ->asArray()
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockClient = new Client(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockClient->resetAuthToken(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            TrustedAppModel::class,
            $response->getData()['trustedApp']
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnResetAuthToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success' => true,
                            'error'   => false
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockClient = new Client(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockClient->resetAuthToken(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
    }
}
