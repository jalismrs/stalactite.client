<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client
 */
class Client
{
    private const ERROR_SCHEMA = [
        'error' => ['type' => JsonRule::INTEGER_TYPE]
    ];

    private string $host;

    private ?string $userAgent = null;

    private ?HttpClientInterface $httpClient = null;

    private ?LoggerInterface $logger = null;

    private ?JsonSchema $errorSchema = null;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    private static function getRequestOptions(array $options): array
    {
        $requestOptions = [];

        if (isset($options['jwt'])) {
            $requestOptions['headers']['X-API-TOKEN'] = $options['jwt'];
        }

        if (isset($options['json'])) {
            $requestOptions['json'] = $options['json'];
        }

        if (isset($options['query'])) {
            $requestOptions['query'] = $options['query'];
        }

        return $requestOptions;
    }

    public function getLogger(): LoggerInterface
    {
        if (!($this->logger instanceof LoggerInterface)) {
            $this->logger = $this->createDefaultLogger();
        }

        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function getHttpClient(): HttpClientInterface
    {
        if (!($this->httpClient instanceof HttpClientInterface)) {
            $this->httpClient = $this->createDefaultHttpClient();
        }

        return $this->httpClient;
    }

    public function setHttpClient(HttpClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    private function getErrorSchema(): JsonSchema
    {
        if (!($this->errorSchema instanceof JsonSchema)) {
            $this->errorSchema = new JsonSchema(self::ERROR_SCHEMA);
        }

        return $this->errorSchema;
    }

    /*
     * -------------------------------------------------------------------------
     * default factories -------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    private function createDefaultLogger(): LoggerInterface
    {
        return new NullLogger();
    }

    private function createDefaultHttpClient(): HttpClientInterface
    {
        return HttpClient::create(
            [
                'base_uri' => $this->host,
                'headers' => $this->userAgent ? ['User-Agent' => $this->userAgent] : []
            ]
        );
    }

    /*
     * -------------------------------------------------------------------------
     * API calls ---------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * @param Endpoint $endpoint
     * @param array $options
     * @return Response
     * @throws ClientException
     */
    public function request(Endpoint $endpoint, array $options = []): Response
    {
        // prepare request
        $method = $endpoint->getMethod();
        $uri = $endpoint->getUri();

        // uri paramteters must be an array or will be ignored
        if (isset($options['uriParameters']) && is_array($options['uriParameters'])) {
            $uri = sprintf($endpoint->getUri(), ...$options['uriParameters']);
            unset($options['uriParameters']);
        }

        $requestOptions = self::getRequestOptions($options);

        $this->getLogger()->notice('API call', [
            'method' => $method,
            'uri' => $uri,
            'options' => $requestOptions,
        ]);

        // exec request
        try {
            $response = $this->getHttpClient()->request($method, $uri, $requestOptions);
            $responseCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $transportException) {
            $clientException = new ClientException('Error while contacting Stalactite API', ClientException::REQUEST_FAILED, $transportException);
            $this->getLogger()->error($clientException);
            throw $clientException;
        }

        if ($responseCode < 200 || $responseCode >= 300) { // not a 2XX => errors
            return $this->handleError($response);
        }

        return $this->handleResponse($endpoint, $response);
    }

    /**
     * @param ResponseInterface $response
     * @return Response
     * @throws ClientException
     */
    private function handleError(ResponseInterface $response): Response
    {
        $responseInfos = $this->getResponseInfos($response);

        try {
            $data = new JsonData($responseInfos['body']);
            $this->getErrorSchema()->validate($data);
        } catch (InvalidDataException $e) { // throw ClientException if invalid error format
            $clientException = new ClientException('Invalid response from Stalactite API', ClientException::INVALID_RESPONSE, $e);
            $this->getLogger()->error($clientException);
            throw $clientException;
        }

        return new Response($responseInfos['code'], $responseInfos['headers'], $data->getData());
    }

    /**
     * @param Endpoint $endpoint
     * @param ResponseInterface $response
     * @return Response
     * @throws ClientException
     */
    private function handleResponse(Endpoint $endpoint, ResponseInterface $response): Response
    {
        $responseInfos = $this->getResponseInfos($response);
        $content = $responseInfos['body'];

        if ($schema = $endpoint->getResponseValidationSchema()) {

            // validate
            try {
                $responseBody = new JsonData($responseInfos['body']);
            } catch (InvalidDataException $e) {
                $clientException = new ClientException('Invalid json response from Stalactite API', ClientException::INVALID_JSON_RESPONSE, $e);
                $this->getLogger()->error($clientException);
                throw $clientException;
            }

            try {
                $schema->validate($responseBody);
            } catch (InvalidDataException $e) {
                $clientException = new ClientException('Invalid Stalactite API response format', ClientException::INVALID_RESPONSE_FORMAT, $e);
                $this->getLogger()->error($clientException);
                throw $clientException;
            }

            $content = $responseBody->getData();

            // format the validated response - if needed
            if ($formatter = $endpoint->getResponseFormatter()) {
                $content = $formatter($content);
            }
        }

        return new Response($responseInfos['code'], $responseInfos['headers'], $content);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws ClientException
     */
    private function getResponseInfos(ResponseInterface $response): array
    {
        try {
            return [
                'code' => $response->getStatusCode(),
                'headers' => $response->getHeaders(false),
                'body' => $response->getContent(false)
            ];
        } catch (Throwable $t) {
            $clientException = new ClientException('Error while contacting Stalactite API', ClientException::REQUEST_FAILED, $t);
            $this->getLogger()->error($clientException);
            throw $clientException;
        }
    }
}
