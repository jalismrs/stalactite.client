<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use function json_encode;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
class ClientTest extends
    TestCase
{
    /**
     * testGetHost
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetHost() : void
    {
        $host       = 'http://fakeHost';
        $mockClient = new Client($host);
        
        self::assertSame(
            $host,
            $mockClient->getHost()
        );
    }
    
    /**
     * testGetUserAgentDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetUserAgentDefault() : void
    {
        $mockClient = new Client('http://fakeHost');
        
        self::assertNull($mockClient->getUserAgent());
    }
    
    /**
     * testGetUserAgent
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetUserAgent() : void
    {
        $userAgent  = 'fake user agent';
        $mockClient = new Client('http://fakeHost');
        $mockClient->setUserAgent($userAgent);
        
        self::assertSame(
            $userAgent,
            $mockClient->getUserAgent()
        );
    }
    
    /**
     * testGetHttpClient
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetHttpClient() : void
    {
        $httpClient = new MockHttpClient();
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient($httpClient);
        
        self::assertSame(
            $httpClient,
            $mockClient->getHttpClient()
        );
    }
    
    /**
     * testGetLogger
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetLogger() : void
    {
        $logger     = new TestLogger();
        $mockClient = new Client('http://fakeHost');
        $mockClient->setLogger($logger);
        
        self::assertSame(
            $logger,
            $mockClient->getLogger()
        );
    }
    
    /**
     * testGetLoggerDefault
     *
     * @return void
     *
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetLoggerDefault() : void
    {
        $mockClient = new Client('http://fakeHost');
        
        self::assertInstanceOf(
            NullLogger::class,
            $mockClient->getLogger()
        );
    }
    
    /**
     * testRequest
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testRequest() : void
    {
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => null,
                        'prout'   => 42,
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $response = $mockClient->request(
            (new Request(
                'fake endpoint'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'prout' => $response['prout'],
                        ];
                    }
                )
                ->setValidation(
                    [
                        'prout' => [
                            'type' => JsonRule::INTEGER_TYPE,
                        ],
                    ]
                )
        );
    
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertSame(
            [
                'prout' => 42,
            ],
            $response->getData()
        );
    }
    
    /**
     * testRequestNotJson
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testRequestNotJson() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Invalid json response from Stalactite API');
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create('not a JSON')
        );
        
        $mockClient->request(
            new Request(
                'fake endpoint'
            )
        );
    }
    
    /**
     * testRequestInvalidResponse
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testRequestInvalidResponse() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessageMatches('#^Invalid response from Stalactite API: #');
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => null,
                        'prout'   => 'plop',
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $mockClient->request(
            (new Request(
                'fake endpoint'
            ))
                ->setValidation(
                    [
                        'prout' => [
                            'type' => JsonRule::INTEGER_TYPE,
                        ],
                    ]
                )
        );
    }
}
