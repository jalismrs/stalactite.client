<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
     * @param Request $request
     *
     * @return Response
     *
     * @throws ClientException
     * @throws Exception\SerializerException
     */
    public function request(Request $request) : Response
    {
        $content = $this->getResponse(
            $request
        );
        
        $data = $this->validateResponse(
            $request,
            $content
        );
        
        return $this->buildResponse(
            $request,
            $data
        );
    }
    
    /**
     * getResponse
     *
     * @param Request $request
     *
     * @return string
     *
     * @throws ClientException
     * @throws Exception\SerializerException
     */
    private function getResponse(Request $request) : string
    {
        $method  = $request->getMethod();
        $uri     = $request->getUri();
        $options = $this->getRequestOptions($request);
        
        $this
            ->getLogger()
            ->debug(
                'API call',
                [
                    'method'  => $method,
                    'uri'     => $uri,
                    'options' => $options,
                ]
            );
        
        try {
            $response = $this
                ->getHttpClient()
                ->request(
                    $method,
                    $uri,
                    $options
                );
        } catch (TransportExceptionInterface $transportException) {
            $this
                ->getLogger()
                ->error($transportException);
            
            throw new ClientException(
                'Error while contacting Stalactite API',
                ClientException::CLIENT_TRANSPORT,
                $transportException
            );
        }
        
        try {
            $content = $response->getContent(false);
        } catch (ExceptionInterface $exception) {
            $this
                ->getLogger()
                ->error($exception);
            
            throw new ClientException(
                'Invalid json response from Stalactite API',
                ClientException::INVALID_API_RESPONSE,
                $exception
            );
        }
        
        return $content;
    }
    
    /**
     * getRequestOptions
     *
     * @param Request $request
     *
     * @return array
     *
     * @throws Exception\SerializerException
     */
    private function getRequestOptions(Request $request) : array
    {
        $json            = $request->getJson();
        $jwt             = $request->getJwt();
        $queryParameters = $request->getQueryParameters();
        
        $options = array_replace_recursive(
            $request->getOptions(),
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ],
            $json === null
                ? []
                : [
                'json' => $json,
            ],
            $jwt === null
                ? []
                : [
                'headers' => [
                    'X-API-TOKEN' => $jwt,
                ],
            ],
            $queryParameters === null
                ? []
                : [
                'query' => $queryParameters,
            ]
        );
        
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
                                             $request->getNormalization() ?? []
                                         );
        }
        
        return $options;
    }
    
    /**
     * getLogger
     *
     * @return LoggerInterface
     */
    public function getLogger() : LoggerInterface
    {
        if ($this->logger === null) {
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
        if ($this->httpClient === null) {
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
    
    /*
     * -------------------------------------------------------------------------
     * API calls ---------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
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
    
    /**
     * validateResponse
     *
     * @param Request $request
     * @param string  $content
     *
     * @return array
     *
     * @throws ClientException
     */
    private function validateResponse(
        Request $request,
        string $content
    ) : array {
        $jsonData = new JsonData();
        try {
            $jsonData->setData($content);
        } catch (InvalidDataException $invalidDataException) {
            $this
                ->getLogger()
                ->error($invalidDataException);
            
            throw new ClientException(
                'Invalid json response from Stalactite API',
                ClientException::INVALID_API_RESPONSE,
                $invalidDataException
            );
        }
        
        $schema = new JsonSchema();
        try {
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
                    $request->getValidation() ?? []
                )
            );
        } catch (InvalidSchemaException $invalidSchemaException) {
            $this
                ->getLogger()
                ->error($invalidSchemaException);
            
            throw new ClientException(
                'Invalid json response from Stalactite API',
                ClientException::INVALID_API_RESPONSE,
                $invalidSchemaException
            );
        }
        
        try {
            $isValid = $schema->validate($jsonData);
        } catch (InvalidDataTypeException $invalidDataTypeException) {
            $this
                ->getLogger()
                ->error($invalidDataTypeException);
            
            throw new ClientException(
                'Invalid json response from Stalactite API',
                ClientException::INVALID_API_RESPONSE,
                $invalidDataTypeException
            );
        }
        
        if (!$isValid) {
            $clientException = new ClientException(
                'Invalid response from Stalactite API: ' . $schema->getLastError(),
                ClientException::INVALID_API_RESPONSE
            );
            
            $this
                ->getLogger()
                ->error($clientException);
            
            throw $clientException;
        }
        
        $data = $jsonData->getData();
        if ($data === null) {
            $clientException = new ClientException(
                'Invalid response from Stalactite API: response is null',
                ClientException::INVALID_API_RESPONSE
            );
            
            $this
                ->getLogger()
                ->error($clientException);
            
            throw $clientException;
        }
        
        return $data;
    }
    
    /**
     * buildResponse
     *
     * @param Request $request
     * @param array   $data
     *
     * @return Response
     */
    private function buildResponse(
        Request $request,
        array $data
    ) : Response {
        $response = $request->getResponse();
        
        return new Response(
            $data['success'],
            $data['error'],
            $response === null
                ? null
                : $response($data)
        );
    }
}
