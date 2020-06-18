<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\ApiError;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use JsonException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Throwable;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
class ClientTest extends TestCase
{
    /**
     * testGetHost
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetHost(): void
    {
        $host = 'http://fakeHost';
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
    public function testGetUserAgentDefault(): void
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
    public function testGetUserAgent(): void
    {
        $userAgent = 'fake user agent';
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
    public function testGetHttpClient(): void
    {
        $httpClient = new MockHttpClient();
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient($httpClient);

        self::assertSame(
            $httpClient,
            $mockClient->getHttpClient()
        );
    }

    public function testGetLogger(): void
    {
        $logger = new TestLogger();
        $mockClient = new Client('http://fakeHost');
        $mockClient->setLogger($logger);

        self::assertSame(
            $logger,
            $mockClient->getLogger()
        );
    }

    public function testGetLoggerDefault(): void
    {
        $mockClient = new Client('http://fakeHost');

        self::assertInstanceOf(
            NullLogger::class,
            $mockClient->getLogger()
        );
    }

    public function testExceptionThrownOnInvalidAPIHost(): void
    {
        $mockClient = new Client('invalidHost');
        $endpoint = new Endpoint('/');

        $throwable = null;
        try {
            $mockClient->request($endpoint);
        } catch (Throwable $t) {
            $throwable = $t;
        }

        self::assertInstanceOf(ClientException::class, $throwable);
        self::assertSame(ClientException::REQUEST_FAILED, $throwable->getCode());
        /** @var ClientException $throwable */
        self::assertNull($throwable->getResponse());
    }

    /**
     * @throws ClientException
     */
    public function testRequest(): void
    {
        $responseBody = 'response body';

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create($responseBody)
        );

        $endpoint = new Endpoint('/');
        $response = $mockClient->request($endpoint);

        self::assertSame(
            $responseBody,
            $response->getBody()
        );
    }

    /**
     * @throws ClientException
     * @throws JsonException
     */
    public function testRequestWithValidationSchema(): void
    {
        $responseBody = ['key' => 'value'];

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode($responseBody, JSON_THROW_ON_ERROR, 512)
            )
        );

        $endpoint = new Endpoint('/');
        $endpoint->setResponseValidationSchema(new JsonSchema([
            'key' => ['type' => JsonRule::STRING_TYPE]
        ]));

        $response = $mockClient->request($endpoint);

        self::assertSame(
            $responseBody,
            $response->getBody()
        );
    }

    public function testRequestWithInvalidJsonData(): void
    {
        $responseBody = 'invalid{}json';

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create($responseBody)
        );

        $endpoint = new Endpoint('/');
        // need validation schema to trigger response body transformation from json to php array
        $endpoint->setResponseValidationSchema(new JsonSchema([
            'key' => ['type' => JsonRule::STRING_TYPE]
        ]));

        $throwable = null;
        try {
            $mockClient->request($endpoint);
        } catch (Throwable $t) {
            $throwable = $t;
        }

        self::assertInstanceOf(ClientException::class, $throwable);
        self::assertSame(ClientException::INVALID_JSON_RESPONSE, $throwable->getCode());

        /** @var ClientException $throwable */

        $response = $throwable->getResponse();
        self::assertInstanceOf(Response::class, $response);
        self::assertSame($responseBody, $response->getBody());
    }

    /**
     * @throws JsonException
     */
    public function testRequestWithInvalidDataFormat(): void
    {
        // `key` item missing
        $responseBody = [
            'invalid' => 'body'
        ];

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(json_encode($responseBody, JSON_THROW_ON_ERROR, 512))
        );

        $endpoint = new Endpoint('/');
        // need validation schema to trigger response body transformation from json to php array
        $endpoint->setResponseValidationSchema(new JsonSchema([
            'key' => ['type' => JsonRule::STRING_TYPE]
        ]));

        $throwable = null;
        try {
            // this will throw : invalid response body schema
            $mockClient->request($endpoint);
        } catch (Throwable $t) {
            $throwable = $t;
        }

        // test exception
        self::assertInstanceOf(ClientException::class, $throwable);
        self::assertSame(ClientException::INVALID_RESPONSE_FORMAT, $throwable->getCode());

        /** @var ClientException $throwable */

        // test exception response property
        $response = $throwable->getResponse();
        self::assertInstanceOf(Response::class, $response);
        // the response body valid json string
        // it should be transformed into a PHP array
        self::assertSame($responseBody, $response->getBody());
    }

    /**
     * @throws ClientException
     * @throws JsonException
     */
    public function testRequestWithValidationSchemaAndResponseFormatter(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(json_encode([
                'item' => '1'
            ], JSON_THROW_ON_ERROR, 512))
        );

        $endpoint = new Endpoint('/');
        $endpoint
            ->setResponseValidationSchema(new JsonSchema([
                'item' => ['type' => JsonRule::STRING_TYPE]
            ]))
            ->setResponseFormatter(
                static function (array $response): array {
                    $response['item'] = (int)$response['item']; // cast `item` item into an integer
                    return $response;
                }
            );

        $response = $mockClient->request($endpoint);

        self::assertSame(
            ['item' => 1],
            $response->getBody()
        );
    }

    /**
     * @throws ClientException
     */
    public function testFormatterNotAppliedIfNoValidationSchema(): void
    {
        $responseBody = 'abcdef';

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create($responseBody)
        );

        $endpoint = new Endpoint('/');
        // not applied due to missing validation schema
        $endpoint->setResponseFormatter(
            static function (string $response): string {
                return str_replace('a', 'b', $response);
            }
        );

        $response = $mockClient->request($endpoint);

        // response not changed
        self::assertSame(
            $responseBody,
            $response->getBody()
        );
    }

    /**
     * @throws ClientException
     * @throws JsonException
     * @throws NormalizerException
     */
    public function testErrorResponse(): void
    {
        $apiError = new ApiError('type', 1, null);

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()->normalize($apiError, [
                        AbstractNormalizer::GROUPS => ['main']
                    ]),
                    JSON_THROW_ON_ERROR, 512
                ),
                ['http_code' => 400] // considered as an error
            )
        );

        $endpoint = new Endpoint('/');
        $response = $mockClient->request($endpoint);

        self::assertInstanceOf(ApiError::class, $response->getBody());
    }

    public function testInvalidErrorResponse(): void
    {
        $responseBody = 'invalid-api-error-format';
        $responseCode = 404;

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                $responseBody, ['http_code' => $responseCode] // invalid API error format
            )
        );

        $endpoint = new Endpoint('/');

        $throwable = null;
        try {
            // this will throw : response body is not a valid json
            $mockClient->request($endpoint);
        } catch (Throwable $t) {
            $throwable = $t;
        }

        // test exception
        self::assertInstanceOf(ClientException::class, $throwable);
        self::assertSame(ClientException::INVALID_RESPONSE, $throwable->getCode());

        /** @var ClientException $throwable */

        // test exception response propery
        $response = $throwable->getResponse();
        self::assertInstanceOf(Response::class, $response);
        self::assertSame($responseBody, $response->getBody());
        self::assertSame($responseCode, $response->getCode());
    }

    /**
     * @throws ClientException
     */
    public function testResponseKeepsApiResponseCode(): void
    {
        $responseCode = 204; // not 200 (default response http code)

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create('', [
                'http_code' => $responseCode
            ])
        );

        $endpoint = new Endpoint('/');
        $response = $mockClient->request($endpoint);

        self::assertSame($responseCode, $response->getCode());
    }

    /**
     * @throws ClientException
     */
    public function testResponseKeepsApiResponseHeaders(): void
    {
        $responseHeaders = [
            'fake-header' => ['fakeValue'],
            'fake-header2' => ['fakeValue2']
        ];

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create('', [
                'response_headers' => $responseHeaders
            ])
        );

        $endpoint = new Endpoint('/');
        $response = $mockClient->request($endpoint);

        self::assertSame(
            $responseHeaders,
            $response->getHeaders()
        );
    }
}
