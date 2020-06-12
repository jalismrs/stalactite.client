<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use JsonException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

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

    /**
     * @throws ClientException
     */
    public function testExceptionThrownOnInvalidAPIHost(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::REQUEST_FAILED);

        $mockClient = new Client('invalidHost');

        $endpoint = new Endpoint('/');
        $mockClient->request($endpoint);
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

    /**
     * @throws ClientException
     */
    public function testRequestWithInvalidJsonData(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JSON_RESPONSE);

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

        $mockClient->request($endpoint);
    }

    /**
     * @throws ClientException
     * @throws JsonException
     */
    public function testRequestWithInvalidDataFormat(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_RESPONSE_FORMAT);

        // `key` item missing
        $responseBody = json_encode([], JSON_THROW_ON_ERROR, 512);

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create($responseBody)
        );

        $endpoint = new Endpoint('/');
        // need validation schema to trigger response body transformation from json to php array
        $endpoint->setResponseValidationSchema(new JsonSchema([
            'key' => ['type' => JsonRule::STRING_TYPE]
        ]));

        $mockClient->request($endpoint);
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
        $endpoint->setResponseValidationSchema(new JsonSchema([
            'item' => ['type' => JsonRule::STRING_TYPE]
        ]));
        // cast `item` item into an integer
        $endpoint->setResponseFormatter(
            static function (array $response): array {
                $response['item'] = (int)$response['item'];
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
        // not apply due to missing validation schema
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
     */
    public function testErrorResponse(): void
    {
        $responseBody = ['error' => 1];

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode($responseBody, JSON_THROW_ON_ERROR, 512),
                ['http_code' => 500] // considered as an error <= 400
            )
        );

        $endpoint = new Endpoint('/');
        $response = $mockClient->request($endpoint);

        // response not changed
        self::assertSame(
            $responseBody,
            $response->getBody()
        );
    }

    /**
     * @throws ClientException
     */
    public function testInvalidErrorResponse(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_RESPONSE);

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                '', ['http_code' => 404] // invalid API error format
            )
        );

        $endpoint = new Endpoint('/');
        $mockClient->request($endpoint);
    }

    /**
     * @throws ClientException
     */
    public function testResponseKeepsApiResponseCode(): void
    {
        $responseCode = 204; // not default library http code

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            MockHttpClientFactory::create('', [
                'http_code' => $responseCode
            ])
        );

        $endpoint = new Endpoint('/');
        $response = $mockClient->request($endpoint);

        self::assertSame(
            $responseCode,
            $response->getCode()
        );
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
