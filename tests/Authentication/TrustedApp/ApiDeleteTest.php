<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\Authentication\TrustedApp;

use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\Authentication\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiDeleteTest
 *
 * @package Jalismrs\Stalactite\Test\Authentication\TrustedApp
 */
class ApiDeleteTest extends
    TestCase
{
    /**
     * testDelete
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
    public function testDelete() : void
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
        
        $mockClient = new Client(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $trustedAppModel = ModelFactory::getTestableTrustedApp();
        
        $response = $mockClient->delete(
            $trustedAppModel->getUid(),
            $trustedAppModel->getResetToken(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
    }
    
    /**
     * testThrowOnDelete
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowOnDelete() : void
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
        
        $trustedAppModel = ModelFactory::getTestableTrustedApp();
        
        $mockClient->delete(
            $trustedAppModel->getUid(),
            $trustedAppModel->getResetToken(),
            'fake user jwt'
        );
    }
}
