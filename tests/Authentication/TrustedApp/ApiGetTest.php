<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\Authentication\TrustedApp;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\Authentication\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiGetTest
 *
 * @package Jalismrs\Stalactite\Test\Authentication\TrustedApp
 */
class ApiGetTest extends
    TestCase
{
    /**
     * testGet
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testGet() : void
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
        
        $response = $mockClient->get(
            ModelFactory::getTestableTrustedApp()
                        ->getUid(),
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
     * testInvalidResponseOnGet
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testInvalidResponseOnGet() : void
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
        
        $mockClient = new Client(
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
}
