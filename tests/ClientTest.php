<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\ApiError;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Throwable;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Client\Tests
 *
 * @covers \Jalismrs\Stalactite\Client\Client
 */
class ClientTest extends
    TestCase
{
    /**
     * testGetHttpClient
     *
     * @return void
     */
    public function testGetHttpClient() : void
    {
        $httpClient      = new MockHttpClient();
        $systemUnderTest = ClientFactory::createBasicClient();
        
        $output = $systemUnderTest->getHttpClient();
        
        self::assertNotSame(
            $httpClient,
            $output
        );
    }
    
    /**
     * testGetLogger
     *
     * @return void
     */
    public function testGetLogger() : void
    {
        $logger          = new TestLogger();
        $systemUnderTest = ClientFactory::createBasicClient();
        
        $output = $systemUnderTest->getLogger();
        
        self::assertNotSame(
            $logger,
            $output
        );
    }
    
    /**
     * testExceptionThrownOnInvalidAPIHost
     *
     * @return void
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testExceptionThrownOnInvalidAPIHost() : void
    {
        $systemUnderTest = ClientFactory::createBasicClient();
        $endpoint        = new Endpoint('/');
        
        $throwable = null;
        try {
            $systemUnderTest->request($endpoint);
        } catch (Throwable $t) {
            $throwable = $t;
        }
        
        /** @var ClientException $throwable */
        self::assertInstanceOf(
            ClientException::class,
            $throwable
        );
        self::assertSame(
            ClientException::REQUEST_FAILED,
            $throwable->getCode()
        );
        self::assertNull(
            $throwable->getResponse()
        );
    }
    
    /**
     * testRequest
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testRequest() : void
    {
        $responseBody = 'response body';
        
        $systemUnderTest = ClientFactory::createBasicClient();
        $systemUnderTest->setHttpClient(
            MockHttpClientFactory::create($responseBody)
        );
        
        $endpoint = new Endpoint('/');
        $response = $systemUnderTest->request($endpoint);
        
        $output = $response->getBody();
        
        self::assertSame(
            $responseBody,
            $output
        );
    }
    
    /**
     * testRequestWithValidationSchema
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \JsonException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testRequestWithValidationSchema() : void
    {
        $responseBody = ['key' => 'value'];
        
        $systemUnderTest = ClientFactory::createBasicClient();
        $systemUnderTest->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    $responseBody,
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $endpoint = new Endpoint('/');
        $endpoint->setResponseValidationSchema(
            new JsonSchema(
                [
                    'key' => ['type' => JsonRule::STRING_TYPE],
                ]
            )
        );
        
        $response = $systemUnderTest->request($endpoint);
        
        $output = $response->getBody();
        
        self::assertSame(
            $responseBody,
            $output
        );
    }
    
    /**
     * testRequestWithInvalidJsonData
     *
     * @return void
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testRequestWithInvalidJsonData() : void
    {
        $responseBody = 'invalid{}json';
        
        $systemUnderTest = ClientFactory::createBasicClient();
        $systemUnderTest->setHttpClient(
            MockHttpClientFactory::create($responseBody)
        );
        
        $endpoint = new Endpoint('/');
        // need validation schema to trigger response body transformation from json to php array
        $endpoint->setResponseValidationSchema(
            new JsonSchema(
                [
                    'key' => ['type' => JsonRule::STRING_TYPE],
                ]
            )
        );
        
        $throwable = null;
        try {
            $systemUnderTest->request($endpoint);
        } catch (Throwable $t) {
            $throwable = $t;
        }
        
        self::assertInstanceOf(
            ClientException::class,
            $throwable
        );
        self::assertSame(
            ClientException::INVALID_JSON_RESPONSE,
            $throwable->getCode()
        );
        
        /** @var ClientException $throwable */
        
        $response = $throwable->getResponse();
        self::assertInstanceOf(
            Response::class,
            $response
        );
        self::assertSame(
            $responseBody,
            $response->getBody()
        );
    }
    
    /**
     * testRequestWithInvalidDataFormat
     *
     * @return void
     *
     * @throws \JsonException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testRequestWithInvalidDataFormat() : void
    {
        // `key` item missing
        $responseBody = [
            'invalid' => 'body',
        ];
        
        $systemUnderTest = ClientFactory::createBasicClient();
        $systemUnderTest->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    $responseBody,
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $endpoint = new Endpoint('/');
        // need validation schema to trigger response body transformation from json to php array
        $endpoint->setResponseValidationSchema(
            new JsonSchema(
                [
                    'key' => ['type' => JsonRule::STRING_TYPE],
                ]
            )
        );
        
        $throwable = null;
        try {
            // this will throw : invalid response body schema
            $systemUnderTest->request($endpoint);
        } catch (Throwable $t) {
            $throwable = $t;
        }
        
        // test exception
        self::assertInstanceOf(
            ClientException::class,
            $throwable
        );
        self::assertSame(
            ClientException::INVALID_RESPONSE_FORMAT,
            $throwable->getCode()
        );
        
        /** @var ClientException $throwable */
        
        // test exception response property
        $response = $throwable->getResponse();
        self::assertInstanceOf(
            Response::class,
            $response
        );
        // the response body valid json string
        // it should be transformed into a PHP array
        self::assertSame(
            $responseBody,
            $response->getBody()
        );
    }
    
    /**
     * testRequestWithValidationSchemaAndResponseFormatter
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \JsonException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testRequestWithValidationSchemaAndResponseFormatter() : void
    {
        $systemUnderTest = ClientFactory::createBasicClient();
        $systemUnderTest->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'item' => '1',
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $endpoint = new Endpoint('/');
        $endpoint
            ->setResponseValidationSchema(
                new JsonSchema(
                    [
                        'item' => ['type' => JsonRule::STRING_TYPE],
                    ]
                )
            )
            ->setResponseFormatter(
                static function(array $response) : array {
                    $response['item'] = (int)$response['item']; // cast `item` item into an integer
                    
                    return $response;
                }
            );
        
        $response = $systemUnderTest->request($endpoint);
        
        $output = $response->getBody();
        
        self::assertSame(
            ['item' => 1],
            $output
        );
    }
    
    /**
     * testFormatterNotAppliedIfNoValidationSchema
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testFormatterNotAppliedIfNoValidationSchema() : void
    {
        $responseBody = 'abcdef';
        
        $systemUnderTest = ClientFactory::createBasicClient();
        $systemUnderTest->setHttpClient(
            MockHttpClientFactory::create($responseBody)
        );
        
        $endpoint = new Endpoint('/');
        // not applied due to missing validation schema
        $endpoint->setResponseFormatter(
            static function(string $response) : string {
                return str_replace(
                    'a',
                    'b',
                    $response
                );
            }
        );
        
        $response = $systemUnderTest->request($endpoint);
        
        $output = $response->getBody();
        
        // response not changed
        self::assertSame(
            $responseBody,
            $output
        );
    }
    
    /**
     * testErrorResponse
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \Jalismrs\Stalactite\Client\Exception\NormalizerException
     * @throws \JsonException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testErrorResponse() : void
    {
        $apiError = new ApiError(
            'type',
            1,
            null
        );
        
        $systemUnderTest = ClientFactory::createBasicClient();
        $systemUnderTest->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                              ->normalize(
                                  $apiError,
                                  [
                                      AbstractNormalizer::GROUPS => ['main'],
                                  ]
                              ),
                    JSON_THROW_ON_ERROR
                ),
                ['http_code' => 400] // considered as an error
            )
        );
        
        $endpoint = new Endpoint('/');
        $response = $systemUnderTest->request($endpoint);
        
        $output = $response->getBody();
        
        self::assertInstanceOf(
            ApiError::class,
            $output
        );
    }
    
    /**
     * testInvalidErrorResponse
     *
     * @return void
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testInvalidErrorResponse() : void
    {
        $responseBody = 'invalid-api-error-format';
        $responseCode = 404;
        
        $systemUnderTest = ClientFactory::createBasicClient();
        $systemUnderTest->setHttpClient(
            MockHttpClientFactory::create(
                $responseBody,
                ['http_code' => $responseCode] // invalid API error format
            )
        );
        
        $endpoint = new Endpoint('/');
        
        $throwable = null;
        try {
            // this will throw : response body is not a valid json
            $systemUnderTest->request($endpoint);
        } catch (Throwable $t) {
            $throwable = $t;
        }
        
        // test exception
        self::assertInstanceOf(
            ClientException::class,
            $throwable
        );
        self::assertSame(
            ClientException::INVALID_RESPONSE,
            $throwable->getCode()
        );
        
        /** @var ClientException $throwable */
        
        // test exception response propery
        $response = $throwable->getResponse();
        self::assertInstanceOf(
            Response::class,
            $response
        );
        self::assertSame(
            $responseBody,
            $response->getBody()
        );
        self::assertSame(
            $responseCode,
            $response->getCode()
        );
    }
    
    /**
     * testResponseKeepsApiResponseCode
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testResponseKeepsApiResponseCode() : void
    {
        $responseCode = 204; // not 200 (default response http code)
        
        $systemUnderTest = ClientFactory::createBasicClient();
        $systemUnderTest->setHttpClient(
            MockHttpClientFactory::create(
                '',
                [
                    'http_code' => $responseCode,
                ]
            )
        );
        
        $endpoint = new Endpoint('/');
        $response = $systemUnderTest->request($endpoint);
        
        $output = $response->getCode();
        
        self::assertSame(
            $responseCode,
            $output
        );
    }
    
    /**
     * testResponseKeepsApiResponseHeaders
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testResponseKeepsApiResponseHeaders() : void
    {
        $responseHeaders = [
            'fake-header'  => ['fakeValue'],
            'fake-header2' => ['fakeValue2'],
        ];
        
        $systemUnderTest = ClientFactory::createBasicClient();
        $systemUnderTest->setHttpClient(
            MockHttpClientFactory::create(
                '',
                [
                    'response_headers' => $responseHeaders,
                ]
            )
        );
        
        $endpoint = new Endpoint('/');
        $response = $systemUnderTest->request($endpoint);
        
        $output = $response->getHeaders();
        
        self::assertSame(
            $responseHeaders,
            $output
        );
    }
}
