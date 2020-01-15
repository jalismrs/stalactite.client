<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\Authentication\TrustedApp;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedAppClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\Authentication\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test\Authentication\TrustedApp
 */
class ClientTest extends
    TestCase
{
    /**
     * testGetAllTrustedApps
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testGetAllTrustedApps() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'     => true,
                            'error'       => null,
                            'trustedApps' => [
                                ModelFactory::getTestableTrustedApp()
                                            ->asArray()
                            ]
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockClient = new TrustedAppClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockClient->getAll(
            'fake user jwt'
        );
        
        self::assertTrue($response->success());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            TrustedApp::class,
            $response->getData()['trustedApps']
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testInvalidResponseFromApiWhileGettingTrustedApps() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'     => true,
                            'error'       => null,
                            'trustedApps' => 'wrong response type'
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockClient = new TrustedAppClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockClient->getAll(
            'fake user jwt'
        );
    }
    
    /**
     * testGetTrustedApp
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
    public function testGetTrustedApp() : void
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
        
        $mockClient = new TrustedAppClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockClient->get(
            ModelFactory::getTestableTrustedApp()
                        ->getUid(),
            'fake user jwt'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            TrustedApp::class,
            $response->getData()['trustedApp']
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testInvalidResponseFromApiWhileGettingTrustedApp() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'     => true,
                            'error'       => null,
                            'trustedApps' => 'wrong response type'
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockClient = new TrustedAppClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockClient->get(
            ModelFactory::getTestableTrustedApp()
                        ->getUid(),
            'fake user jwt'
        );
    }
    
    /**
     * testCreateTrustedApp
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
    public function testCreateTrustedApp() : void
    {
        $ta             = ModelFactory::getTestableTrustedApp();
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'    => true,
                            'error'      => null,
                            'trustedApp' => array_merge($ta->asArray(), ['resetToken' => $ta->getResetToken()])
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockClient = new TrustedAppClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockClient->create(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            TrustedApp::class,
            $response->getData()['trustedApp']
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnCreateTrustedApp() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'    => true,
                            'error'      => null,
                            'trustedApp' => []
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockClient = new TrustedAppClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockClient->create(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
    }
    
    /**
     * testUpdateTrustedApp
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testUpdateTrustedApp() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success' => true,
                            'error'   => null
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockClient = new TrustedAppClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockClient->update(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnUpdateTrustedApp() : void
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
        
        $mockClient = new TrustedAppClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockClient->update(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
    }
    
    /**
     * testDeleteTrustedApp
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testDeleteTrustedApp() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success' => true,
                            'error'   => null
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockClient = new TrustedAppClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $ta = ModelFactory::getTestableTrustedApp();
        
        $response = $mockClient->delete(
            $ta->getUid(),
            $ta->getResetToken(),
            'fake user jwt'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnDeleteTrustedApp() : void
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
        
        $mockClient = new TrustedAppClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $ta = ModelFactory::getTestableTrustedApp();
        
        $mockClient->delete(
            $ta->getUid(),
            $ta->getResetToken(),
            'fake user jwt'
        );
    }
    
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
    public function testResetTrustedAppAuthToken() : void
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
        
        $mockClient = new TrustedAppClient(
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
            TrustedApp::class,
            $response->getData()['trustedApp']
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnResetTrustedAppAuthToken() : void
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
        
        $mockClient = new TrustedAppClient(
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
