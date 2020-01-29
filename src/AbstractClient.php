<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Schema\JsonSchema;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;
use function array_merge_recursive;

/**
 * AbstractClient
 *
 * @package Jalismrs\Stalactite\Client
 */
abstract class AbstractClient
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var null|string
     */
    private $userAgent;
    
    /**
     * AbstractClient constructor.
     *
     * @param string $host
     */
    public function __construct(
        string $host
    ) {
        $this->host = $host;
    }
    
    /**
     * getHost
     *
     * @return string
     */
    public function getHost() : string
    {
        return $this->host;
    }
    
    /**
     * @return HttpClientInterface
     */
    public function getHttpClient() : HttpClientInterface
    {
        if (null === $this->httpClient) {
            $this->httpClient = $this->createDefaultHttpClient();
        }
        
        return $this->httpClient;
    }
    
    /**
     * setHttpClient
     *
     * @param HttpClientInterface $httpClient
     *
     * @return $this
     */
    public function setHttpClient(HttpClientInterface $httpClient) : self
    {
        $this->httpClient = $httpClient;
        
        return $this;
    }
    
    /**
     * createDefaultHttpClient
     *
     * @return HttpClientInterface
     */
    private function createDefaultHttpClient() : HttpClientInterface
    {
        return HttpClient::create(
            array_merge_recursive(
                [
                    'base_uri' => $this->host,
                    'headers'  => [
                        'Content-Type' => 'application/json',
                    ],
                ],
                null === $this->userAgent
                    ? []
                    : [
                    'headers' => [
                        'User-Agent' => $this->userAgent,
                    ],
                ]
            )
        );
    }
    
    /**
     * getLogger
     *
     * @return LoggerInterface
     */
    public function getLogger() : LoggerInterface
    {
        if (null === $this->logger) {
            $this->logger = $this->createDefaultLogger();
        }
    
        return $this->logger;
    }
    
    /**
     * createDefaultLogger
     *
     * @return LoggerInterface
     */
    private function createDefaultLogger(): LoggerInterface
    {
        return new NullLogger();
    }
    
    /**
     * setLogger
     *
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger) : self
    {
        $this->logger = $logger;
        
        return $this;
    }
    
    /**
     * getUserAgent
     *
     * @return null|string
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
     */
    public function setUserAgent(?string $userAgent) : self
    {
        $this->userAgent = $userAgent;
        
        return $this;
    }
    
    /*
     * -------------------------------------------------------------------------
     * API calls ---------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    /**
     * delete
     *
     * @param string     $uri
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    final protected function delete(
        string $uri,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this->request(
            'DELETE',
            $uri,
            $options,
            $schema
        );
    }
    
    /**
     * get
     *
     * @param string     $endpoint
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    final protected function get(
        string $endpoint,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this->request(
            'GET',
            $endpoint,
            $options,
            $schema
        );
    }
    
    /**
     * post
     *
     * @param string     $endpoint
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    final protected function post(
        string $endpoint,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this->request(
            'POST',
            $endpoint,
            $options,
            $schema
        );
    }
    
    /**
     * put
     *
     * @param string     $endpoint
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    final protected function put(
        string $endpoint,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this->request(
            'PUT',
            $endpoint,
            $options,
            $schema
        );
    }
    
    /**
     * request
     *
     * @param string     $method
     * @param string     $endpoint
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    private function request(
        string $method,
        string $endpoint,
        array $options,
        JsonSchema $schema
    ) : array {
        try {
            $response = $this
                ->getHttpClient()
                ->request(
                    $method,
                    "{$this->host}{$endpoint}",
                    $options
                );
        } catch (Throwable $throwable) {
            $exception = new ClientException(
                'Error while contacting Stalactite API',
                ClientException::CLIENT_TRANSPORT,
                $throwable
            );
            
            $this->getLogger()->error($exception);
            
            throw $exception;
        }
        
        $data = new JsonData();
        try {
            $data->setData($response->getContent(false));
        } catch (Throwable $throwable) {
            $exception = new ClientException(
                'Invalid json response from Stalactite API',
                ClientException::INVALID_API_RESPONSE,
                $throwable
            );
    
            $this->getLogger()->error($exception);
    
            throw $exception;
        }
        
        if (!$schema->validate($data)) {
            $exception = new ClientException(
                'Invalid response from Stalactite API: ' . $schema->getLastError(),
                ClientException::INVALID_API_RESPONSE
            );
    
            $this->getLogger()->error($exception);
    
            throw $exception;
        }
        
        $response = $data->getData();
        if (null === $response) {
            $exception = new ClientException(
                'Invalid response from Stalactite API: response is null',
                ClientException::INVALID_API_RESPONSE
            );
    
            $this->getLogger()->error($exception);
    
            throw $exception;
        }
        
        return $response;
    }
}
