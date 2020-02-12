<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client
 */
final class Client
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
     * Client constructor.
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
     * setHost
     *
     * @param string $host
     *
     * @return $this
     */
    public function setHost(string $host) : self
    {
        $this->host = $host;
        
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
    
    /**
     * request
     *
     * @param RequestConfiguration $requestConfiguration
     * @param array                $uriDatas
     * @param array                $options
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws Util\SerializerException
     */
    public function request(
        RequestConfiguration $requestConfiguration,
        array $uriDatas,
        array $options
    ) : Response {
        if (isset($options['json'])) {
            $options = array_replace_recursive(
                $options,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                ]
            );
            
            $options['json'] = Serializer::getInstance()
                                         ->normalize(
                                             $options['json'],
                                             $requestConfiguration->getNormalization() ?? []
                                         );
        }
        
        $uri = $requestConfiguration->getUri($uriDatas);
        
        try {
            $this->getLogger()
                 ->debug(
                     'API call',
                     [
                         'uriDatas' => $uriDatas,
                         'options'  => $options,
                     ]
                 );
            
            $response = $this
                ->getHttpClient()
                ->request(
                    $requestConfiguration->getMethod(),
                    $uri,
                    $options
                );
        } catch (Throwable $throwable) {
            $exception = new ClientException(
                'Error while contacting Stalactite API',
                ClientException::CLIENT_TRANSPORT,
                $throwable
            );
            
            $this->getLogger()
                 ->error($exception);
            
            throw $exception;
        }
        
        $jsonData = new JsonData();
        try {
            $content = $response->getContent(false);
            
            $this->getLogger()
                 ->debug($content);
            
            $jsonData->setData($content);
        } catch (Throwable $throwable) {
            $exception = new ClientException(
                'Invalid json response from Stalactite API',
                ClientException::INVALID_API_RESPONSE,
                $throwable
            );
            
            $this->getLogger()
                 ->error($exception);
            
            throw $exception;
        }
        
        $schema = new JsonSchema();
        $schema->setSchema(
            array_merge(
                [
                    'success' => [
                        'type' => JsonRule::BOOLEAN_TYPE
                    ],
                    'error'   => [
                        'type' => JsonRule::STRING_TYPE,
                        'null' => true
                    ],
                ],
                $requestConfiguration->getValidation() ?? []
            )
        );
        if (!$schema->validate($jsonData)) {
            $exception = new ClientException(
                'Invalid response from Stalactite API: ' . $schema->getLastError(),
                ClientException::INVALID_API_RESPONSE
            );
            
            $this->getLogger()
                 ->error($exception);
            
            throw $exception;
        }
        
        $data = $jsonData->getData();
        if ($data === null) {
            $exception = new ClientException(
                'Invalid response from Stalactite API: response is null',
                ClientException::INVALID_API_RESPONSE
            );
            
            $this->getLogger()
                 ->error($exception);
            
            throw $exception;
        }
        
        $response = $requestConfiguration->getResponse();
        
        return new Response(
            $data['success'],
            $data['error'],
            $response === null
                ? null
                : $response($data)
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
    
    /*
     * -------------------------------------------------------------------------
     * default factories -------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    /**
     * createDefaultLogger
     *
     * @return LoggerInterface
     */
    private function createDefaultLogger() : LoggerInterface
    {
        return new NullLogger();
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
    
    /*
     * -------------------------------------------------------------------------
     * API calls ---------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
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
            [
                'base_uri' => $this->host,
                'headers'  => $this->userAgent
                    ? ['User-Agent' => $this->userAgent]
                    : [],
            ]
        );
    }
}
