<?php
declare(strict_types = 1);

namespace Test\Authentication\TrustedApp;

use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Test\Authentication\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiUpdateTest
 *
 * @package Test\Authentication\TrustedApp
 */
class ApiUpdateTest extends
    TestCase
{
    /**
     * testUpdate
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testUpdate() : void
    {
        $mockClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
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
            )
        );
        
        $response = $mockClient->updateTrustedApp(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
    }
    
    /**
     * testThrowOnUpdate
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowOnUpdate() : void
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
        
        $mockClient->updateTrustedApp(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
    }
}
