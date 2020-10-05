<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class Client
{
    private const CACHE_KEY_FORMAT = '%s %s'; // {method} {uri}

    private string $host;
    private ?string $userAgent = null;
    private ?HttpClientInterface $httpClient = null;
    private ?LoggerInterface $logger = null;
    private ?CacheInterface $cache = null;
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

        if (isset($options['jwt']) && is_string($options['jwt'])) {
            $requestOptions['headers']['X-API-TOKEN'] = $options['jwt'];
        }

        if (isset($options['json']) && is_array($options['json'])) {
            $requestOptions['json'] = $options['json'];
        }

        if (isset($options['query']) && is_array($options['query'])) {
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

    /**
     * @return CacheInterface|null
     * @codeCoverageIgnore
     */
    public function getCache(): ?CacheInterface
    {
        return $this->cache;
    }

    /**
     * @param CacheInterface|null $cache
     * @return Client
     */
    public function setCache(?CacheInterface $cache): Client
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param $data
     * @throws InvalidArgumentException
     */
    public function cache(string $method, string $uri, $data): void
    {
        if ($this->cache instanceof CacheInterface) {
            $this->cache->set(vsprintf(self::CACHE_KEY_FORMAT, [$method, $uri]), $data);
        }
    }

    /**
     * @param string $method
     * @param string $uri
     * @return mixed|null
     * @throws InvalidArgumentException
     */
    public function getFromCache(string $method, string $uri)
    {
        if ($this->cache instanceof CacheInterface) {
            return $this->cache->get(vsprintf(self::CACHE_KEY_FORMAT, [$method, $uri]));
        }

        return null;
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

    /**
     * @return JsonSchema
     */
    private function getErrorSchema(): JsonSchema
    {
        if (!($this->errorSchema instanceof JsonSchema)) {
            $this->errorSchema = new JsonSchema(ApiError::getSchema());
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
     * @throws InvalidArgumentException
     */
    public function request(Endpoint $endpoint, array $options = []): Response
    {
        // prepare request
        $method = $endpoint->getMethod();
        $uri = $endpoint->getUri();

        // uri parameters must be an array or will be ignored
        if (isset($options['uriParameters']) && is_array($options['uriParameters'])) {
            $uri = sprintf($endpoint->getUri(), ...$options['uriParameters']);
            unset($options['uriParameters']);
        }

        if ($cache = $this->getFromCache($method, $uri)) {
            return new Response(200, [], $cache);
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
            $clientException = new ClientException(null, 'Error while contacting Stalactite API', ClientException::REQUEST_FAILED, $transportException);
            $this->getLogger()->error($clientException);
            throw $clientException;
        }

        if ($responseCode < 200 || $responseCode >= 300) { // not a 2XX => errors
            return $this->handleError($response);
        }

        $response = $this->handleResponse($endpoint, $response);

        if ($endpoint->isCacheable()) {
            $this->cache($method, $uri, $response->getBody());
        }

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @return Response
     * @throws ClientException
     */
    private function handleError(ResponseInterface $response): Response
    {
        ['code' => $code, 'headers' => $headers, 'body' => $body] = $this->getResponseInfos($response);

        try {
            $jsonBody = new JsonData($body);
            $this->getErrorSchema()->validate($jsonBody);
        } catch (InvalidDataException $e) {
            $r = new Response($code, $headers, $body);
            $clientException = new ClientException($r, 'Invalid response from Stalactite API', ClientException::INVALID_RESPONSE, $e);
            $this->getLogger()->error($clientException);
            throw $clientException;
        }

        $apiError = new ApiError($jsonBody['type'], $jsonBody['code'], $jsonBody['message']);
        return new Response($code, $headers, $apiError);
    }

    /**
     * @param Endpoint $endpoint
     * @param ResponseInterface $response
     * @return Response
     * @throws ClientException
     */
    private function handleResponse(Endpoint $endpoint, ResponseInterface $response): Response
    {
        ['code' => $code, 'headers' => $headers, 'body' => $body] = $this->getResponseInfos($response);

        if ($schema = $endpoint->getResponseValidationSchema()) {
            // validate
            try {
                $responseBodyAsJson = new JsonData($body);
            } catch (InvalidDataException $e) {
                $r = new Response($code, $headers, $body);
                $clientException = new ClientException($r, 'Invalid json response from Stalactite API', ClientException::INVALID_JSON_RESPONSE, $e);
                $this->getLogger()->error($clientException);
                throw $clientException;
            }

            try {
                $schema->validate($responseBodyAsJson);
            } catch (InvalidDataException $e) {
                $r = new Response($code, $headers, $responseBodyAsJson->getData());
                $clientException = new ClientException($r, 'Invalid Stalactite API response format', ClientException::INVALID_RESPONSE_FORMAT, $e);
                $this->getLogger()->error($clientException);
                throw $clientException;
            }

            $body = $responseBodyAsJson->getData();

            // only format the validated response (format validated)
            if ($formatter = $endpoint->getResponseFormatter()) {
                $body = $formatter($body);
            }
        }

        return new Response($code, $headers, $body);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws ClientException
     * @codeCoverageIgnore
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
            $clientException = new ClientException(null, 'Error while contacting Stalactite API', ClientException::REQUEST_FAILED, $t);
            $this->getLogger()->error($clientException);
            throw $clientException;
        }
    }
}
