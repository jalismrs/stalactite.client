<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Schema\JsonSchema;
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
            $this->httpClient = $this->buildHttpClient();
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
     * buildHttpClient
     *
     * @return HttpClientInterface
     */
    private function buildHttpClient() : HttpClientInterface
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
     * delete
     *
     * @param string     $url
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws InvalidDataTypeException
     * @throws ClientException
     */
    final protected function delete(
        string $url,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this->request(
            'DELETE',
            $url,
            $options,
            $schema
        );
    }
    
    /**
     * get
     *
     * @param string     $url
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws InvalidDataTypeException
     * @throws ClientException
     */
    final protected function get(
        string $url,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this->request(
            'GET',
            $url,
            $options,
            $schema
        );
    }
    
    /**
     * post
     *
     * @param string     $url
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws InvalidDataTypeException
     * @throws ClientException
     */
    final protected function post(
        string $url,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this->request(
            'POST',
            $url,
            $options,
            $schema
        );
    }
    
    /**
     * put
     *
     * @param string     $url
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws InvalidDataTypeException
     * @throws ClientException
     */
    final protected function put(
        string $url,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this->request(
            'PUT',
            $url,
            $options,
            $schema
        );
    }
    
    /**
     * request
     *
     * contact the Stalactite API, check the response based on a JsonSchema and then return the response as an array
     *
     * @param string     $method
     * @param string     $url
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws InvalidDataTypeException
     * @throws ClientException
     */
    private function request(
        string $method,
        string $url,
        array $options,
        JsonSchema $schema
    ) : array {
        try {
            $response = $this
                ->getHttpClient()
                ->request(
                    $method,
                    $url,
                    $options
                );
        } catch (Throwable $throwable) {
            throw new ClientException(
                'Error while contacting Stalactite API',
                ClientException::CLIENT_TRANSPORT,
                $throwable
            );
        }
        
        $data = new JsonData();
        try {
            $data->setData($response->getContent(false));
        } catch (Throwable $throwable) {
            throw new ClientException(
                'Invalid json response from Stalactite API',
                ClientException::INVALID_API_RESPONSE,
                $throwable
            );
        }
        
        if (!$schema->validate($data)) {
            throw new ClientException(
                'Invalid response from Stalactite API: ' . $schema->getLastError(),
                ClientException::INVALID_API_RESPONSE
            );
        }
        
        $response = $data->getData();
        if (null === $response) {
            throw new ClientException(
                'Invalid response from Stalactite API: response is null',
                ClientException::INVALID_API_RESPONSE
            );
        }
        
        return $response;
    }
}
