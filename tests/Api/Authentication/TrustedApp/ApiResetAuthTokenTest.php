<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiResetAuthTokenTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp
 */
class ApiResetAuthTokenTest extends
    TestCase
{
    /**
     * testResetAuthToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     */
    public function testResetAuthToken() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success'    => true,
                        'error'      => null,
                        'trustedApp' => Serializer::getInstance()
                                                  ->normalize(
                                                      ModelFactory::getTestableTrustedApp(),
                                                      [
                                                          AbstractNormalizer::GROUPS => [
                                                              'main',
                                                          ],
                                                      ]
                                                  ),
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $response = $mockService->resetAuthToken(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            TrustedApp::class,
            $response->getData()['trustedApp']
        );
    }
    
    /**
     * testThrowOnResetAuthToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     */
    public function testThrowOnResetAuthToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => false
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $mockService->resetAuthToken(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
    }
}
