<?php
declare(strict_types = 1);

namespace Test\Authentication\TrustedApp;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Test\Authentication\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiResetAuthTokenTest
 *
 * @package Test\Authentication\TrustedApp
 */
class ApiResetAuthTokenTest extends
    TestCase
{
    /**
     * testResetAuthToken
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testResetAuthToken() : void
    {
        $mockClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
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
            )
        );
        
        $response = $mockClient->resetAuthToken(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            TrustedAppModel::class,
            $response->getData()['trustedApp']
        );
    }
    
    /**
     * testThrowOnResetAuthToken
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowOnResetAuthToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
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
            )
        );
        
        $mockClient->resetAuthToken(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
    }
}
