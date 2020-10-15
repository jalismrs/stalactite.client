<?php
declare(strict_types = 1);

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
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

/**
 * Class Client
 *
 * @package Jalismrs\Stalactite\Client
 */
class Client
{
    private const CACHE_KEY_FORMAT = '%s %s'; // {method} {uri}
    
    /**
     * host
     *
     * @var string
     */
    private string $host;
    /**
     * userAgent
     *
     * @var string|null
     */
    private ?string $userAgent = null;
    /**
     * httpClient
     *
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface|null
     */
    private ?HttpClientInterface $httpClient = null;
    /**
     * logger
     *
     * @var \Psr\Log\LoggerInterface|null
     */
    private ?LoggerInterface $logger = null;
    /**
     * cache
     *
     * @var \Psr\SimpleCache\CacheInterface|null
     */
    private ?CacheInterface $cache = null;
    /**
     * errorSchema
     *
     * @var \hunomina\DataValidator\Schema\Json\JsonSchema|null
     */
    private ?JsonSchema $errorSchema = null;
    
    /**
     * Client constructor.
     *
     * @param string $host
     *
     * @codeCoverageIgnore
     */
    public function __construct(string $host)
    {
        $this->host = $host;
    }
    
    /**
     * getHost
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getHost() : string
    {
        return $this->host;
    }
    
    /**
     * getUserAgent
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getUserAgent() : ?string
    {
        return $this->userAgent;
    }
    
    /**
     * setUserAgent
     *
     * @param string|null $userAgent
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setUserAgent(?string $userAgent) : self
    {
        $this->userAgent = $userAgent;
        
        return $this;
    }
    
    /**
     * getCache
     *
     * @return \Psr\SimpleCache\CacheInterface|null
     *
     * @codeCoverageIgnore
     */
    public function getCache() : ?CacheInterface
    {
        return $this->cache;
    }
    
    /**
     * setCache
     *
     * @param \Psr\SimpleCache\CacheInterface|null $cache
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setCache(?CacheInterface $cache) : Client
    {
        $this->cache = $cache;
        
        return $this;
    }
    
    /**
     * request
     *
     * @param \Jalismrs\Stalactite\Client\Util\Endpoint $endpoint
     * @param array                                     $options
     *
     * @return \Jalismrs\Stalactite\Client\Util\Response
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function request(
        Endpoint $endpoint,
        array $options = []
    ) : Response {
        // prepare request
        $method = $endpoint->getMethod();
        $uri    = $endpoint->getUri();
        
        // uri parameters must be an array or will be ignored
        if (isset($options['uriParameters']) && is_array($options['uriParameters'])) {
            $uri = sprintf(
                $endpoint->getUri(),
                ...
                $options['uriParameters']
            );
            unset($options['uriParameters']);
        }
        
        if ($cache = $this->getFromCache(
            $method,
            $uri
        )) {
            return new Response(
                200,
                [],
                $cache
            );
        }
        
        $requestOptions = self::getRequestOptions($options);
        
        $this->getLogger()
             ->notice(
                 'API call',
                 [
                     'method'  => $method,
                     'uri'     => $uri,
                     'options' => $requestOptions,
                 ]
             );
        
        // exec request
        try {
            $response     = $this->getHttpClient()
                                 ->request(
                                     $method,
                                     $uri,
                                     $requestOptions
                                 );
            $responseCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $transportException) {
            $clientException = new ClientException(
                null,
                'Error while contacting Stalactite API',
                ClientException::REQUEST_FAILED,
                $transportException
            );
            $this->getLogger()
                 ->error($clientException);
            throw $clientException;
        }
        
        if ($responseCode < 200 || $responseCode >= 300) { // not a 2XX => errors
            return $this->handleError(
                $endpoint,
                $response
            );
        }
        
        $response = $this->handleResponse(
            $endpoint,
            $response
        );
        
        if ($endpoint->isCacheable()) {
            $this->cache(
                $method,
                $uri,
                $response->getBody()
            );
        }
        
        return $response;
    }
    
    /**
     * getFromCache
     *
     * @param string $method
     * @param string $uri
     *
     * @return mixed|null
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getFromCache(
        string $method,
        string $uri
    ) {
        if ($this->cache instanceof CacheInterface) {
            return $this->cache->get(
                vsprintf(
                    self::CACHE_KEY_FORMAT,
                    [
                        $method,
                        $uri,
                    ]
                )
            );
        }
        
        return null;
    }
    
    /**
     * getRequestOptions
     *
     * @static
     *
     * @param array $options
     *
     * @return array
     */
    private static function getRequestOptions(array $options) : array
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
    
    /**
     * getLogger
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger() : LoggerInterface
    {
        if (!($this->logger instanceof LoggerInterface)) {
            $this->logger = $this->createDefaultLogger();
        }
        
        return $this->logger;
    }
    
    /**
     * setLogger
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setLogger(LoggerInterface $logger) : self
    {
        $this->logger = $logger;
        
        return $this;
    }
    
    /**
     * getHttpClient
     *
     * @return \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    public function getHttpClient() : HttpClientInterface
    {
        if (!($this->httpClient instanceof HttpClientInterface)) {
            $this->httpClient = $this->createDefaultHttpClient();
        }
        
        return $this->httpClient;
    }
    
    /**
     * setHttpClient
     *
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setHttpClient(HttpClientInterface $httpClient) : self
    {
        $this->httpClient = $httpClient;
        
        return $this;
    }
    
    /**
     * handleError
     *
     * @param \Jalismrs\Stalactite\Client\Util\Endpoint       $endpoint
     * @param \Symfony\Contracts\HttpClient\ResponseInterface $response
     *
     * @return \Jalismrs\Stalactite\Client\Util\Response
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     */
    private function handleError(
        Endpoint $endpoint,
        ResponseInterface $response
    ) : Response {
        [
            'code'    => $code,
            'headers' => $headers,
            'body'    => $body,
        ] = $this->getResponseInfos($response);
        
        // if there is a body or the response body is supposed to match a specific schema
        // HTTP HEAD : no body => can "fail" without throwing
        if ($body || $endpoint->getResponseValidationSchema()) {
            try {
                $jsonBody = new JsonData($body);
                $this->getErrorSchema()
                     ->validate($jsonBody);
            } catch (InvalidDataException $e) {
                $r               = new Response(
                    $code,
                    $headers,
                    $body
                );
                $clientException = new ClientException(
                    $r,
                    'Invalid response from Stalactite API',
                    ClientException::INVALID_RESPONSE,
                    $e
                );
                $this->getLogger()
                     ->error($clientException);
                throw $clientException;
            }
            $body = new ApiError(
                $jsonBody['type'],
                $jsonBody['code'],
                $jsonBody['message']
            );
        }
        
        return new Response(
            $code,
            $headers,
            $body
        );
    }
    
    /*
     * -------------------------------------------------------------------------
     * default factories -------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    /**
     * handleResponse
     *
     * @param \Jalismrs\Stalactite\Client\Util\Endpoint       $endpoint
     * @param \Symfony\Contracts\HttpClient\ResponseInterface $response
     *
     * @return \Jalismrs\Stalactite\Client\Util\Response
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     */
    private function handleResponse(
        Endpoint $endpoint,
        ResponseInterface $response
    ) : Response {
        [
            'code'    => $code,
            'headers' => $headers,
            'body'    => $body,
        ] = $this->getResponseInfos($response);
        
        if ($schema = $endpoint->getResponseValidationSchema()) {
            // validate
            try {
                $responseBodyAsJson = new JsonData($body);
            } catch (InvalidDataException $e) {
                $r               = new Response(
                    $code,
                    $headers,
                    $body
                );
                $clientException = new ClientException(
                    $r,
                    'Invalid json response from Stalactite API',
                    ClientException::INVALID_JSON_RESPONSE,
                    $e
                );
                $this->getLogger()
                     ->error($clientException);
                throw $clientException;
            }
            
            try {
                $schema->validate($responseBodyAsJson);
            } catch (InvalidDataException $e) {
                $r               = new Response(
                    $code,
                    $headers,
                    $responseBodyAsJson->getData()
                );
                $clientException = new ClientException(
                    $r,
                    'Invalid Stalactite API response format',
                    ClientException::INVALID_RESPONSE_FORMAT,
                    $e
                );
                $this->getLogger()
                     ->error($clientException);
                throw $clientException;
            }
            
            $body = $responseBodyAsJson->getData();
            
            // only format the validated response (format validated)
            if ($formatter = $endpoint->getResponseFormatter()) {
                $body = $formatter($body);
            }
        }
        
        return new Response(
            $code,
            $headers,
            $body
        );
    }
    
    /**
     * cache
     *
     * @param string $method
     * @param string $uri
     * @param        $data
     *
     * @return void
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function cache(
        string $method,
        string $uri,
        $data
    ) : void {
        if ($this->cache instanceof CacheInterface) {
            $this->cache->set(
                vsprintf(
                    self::CACHE_KEY_FORMAT,
                    [
                        $method,
                        $uri,
                    ]
                ),
                $data
            );
        }
    }
    
    /*
     * -------------------------------------------------------------------------
     * API calls ---------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    /**
     * createDefaultLogger
     *
     * @return \Psr\Log\LoggerInterface
     */
    private function createDefaultLogger() : LoggerInterface
    {
        return new NullLogger();
    }
    
    /**
     * createDefaultHttpClient
     *
     * @return \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private function createDefaultHttpClient() : HttpClientInterface
    {
        return HttpClient::create(
            [
                'base_uri' => $this->host,
                'headers'  => $this->userAgent
                    ? ['User-Agent' => $this->userAgent]
                    : [],
            ]
        );
    }
    
    /**
     * getResponseInfos
     *
     * @param \Symfony\Contracts\HttpClient\ResponseInterface $response
     *
     * @return array
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     *
     * @codeCoverageIgnore
     */
    private function getResponseInfos(ResponseInterface $response) : array
    {
        try {
            return [
                'code'    => $response->getStatusCode(),
                'headers' => $response->getHeaders(false),
                'body'    => $response->getContent(false),
            ];
        } catch (Throwable $t) {
            $clientException = new ClientException(
                null,
                'Error while contacting Stalactite API',
                ClientException::REQUEST_FAILED,
                $t
            );
            $this->getLogger()
                 ->error($clientException);
            throw $clientException;
        }
    }
    
    /**
     * getErrorSchema
     *
     * @return \hunomina\DataValidator\Schema\Json\JsonSchema
     */
    private function getErrorSchema() : JsonSchema
    {
        if (!($this->errorSchema instanceof JsonSchema)) {
            $this->errorSchema = new JsonSchema(ApiError::getSchema());
        }
        
        return $this->errorSchema;
    }
}
