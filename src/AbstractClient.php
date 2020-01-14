<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Schema\JsonSchema;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

/**
 * AbstractClient
 *
 * @package jalismrs\Stalactite\Client
 */
abstract class AbstractClient
{
    /**
     * @var string
     */
    protected $apiHost;
    /**
     * @var HttpClientInterface
     */
    protected $httpClient;
    /**
     * @var null|ResponseInterface
     */
    protected $lastResponse;
    /**
     * @var null|string
     */
    protected $userAgent;
    
    /**
     * AbstractClient constructor.
     *
     * @param string      $apiHost
     * @param null|string $userAgent
     */
    public function __construct(
        string $apiHost,
        ?string $userAgent = null
    ) {
        $this->apiHost   = $apiHost;
        $this->userAgent = $userAgent;
    }
    
    /**
     * getApiHost
     *
     * @return string
     */
    public function getApiHost() : string
    {
        return $this->apiHost;
    }
    
    /**
     * setApiHost
     *
     * @param string $apiHost
     *
     * @return \jalismrs\Stalactite\Client\AbstractClient
     */
    public function setApiHost(string $apiHost) : AbstractClient
    {
        $this->apiHost = $apiHost;
        
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
     * @param null|string $userAgent
     *
     * @return \jalismrs\Stalactite\Client\AbstractClient
     */
    public function setUserAgent(?string $userAgent) : AbstractClient
    {
        $this->userAgent = $userAgent;
        
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
            if ($this->userAgent) {
                $this->httpClient = HttpClient::create(['headers' => ['User-Agent' => $this->userAgent]]);
            } else {
                $this->httpClient = HttpClient::create();
            }
        }
        
        return $this->httpClient;
    }
    
    /**
     * setHttpClient
     *
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient
     *
     * @return $this
     */
    public function setHttpClient(HttpClientInterface $httpClient) : self
    {
        $this->httpClient = $httpClient;
        
        return $this;
    }
    
    /**
     * getLastResponse
     *
     * @return null|\Symfony\Contracts\HttpClient\ResponseInterface
     */
    public function getLastResponse() : ?ResponseInterface
    {
        return $this->lastResponse;
    }
    
    /**
     * setLastResponse
     *
     * @param null|\Symfony\Contracts\HttpClient\ResponseInterface $lastResponse
     *
     * @return \jalismrs\Stalactite\Client\AbstractClient
     */
    public function setLastResponse(?ResponseInterface $lastResponse) : AbstractClient
    {
        $this->lastResponse = $lastResponse;
        
        return $this;
    }
    
    /**
     * request
     *
     * contact the Stalactite API, check the response based on a JsonSchema and then return the response as an array
     *
     * @param string                                     $method
     * @param string                                     $url
     * @param array                                      $options
     * @param \hunomina\Validator\Json\Schema\JsonSchema $schema
     *
     * @return array
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    final protected function request(
        string $method,
        string $url,
        array $options,
        JsonSchema $schema
    ) : array {
        try {
            $response = $this
                ->getHttpClient()
                ->request($method, $url, $options);
        } catch (Throwable $throwable) {
            throw new ClientException(
                'Error while contacting Stalactite API',
                ClientException::CLIENT_TRANSPORT_ERROR,
                $throwable
            );
        }
        
        $this->lastResponse = $response;
        
        $data = new JsonData();
        try {
            $data->setData($response->getContent(false));
        } catch (Throwable $throwable) {
            throw new ClientException(
                'Invalid json response from Stalactite API',
                ClientException::INVALID_API_RESPONSE_ERROR,
                $throwable
            );
        }
        
        if (!$schema->validate($data)) {
            throw new ClientException(
                'Invalid response from Stalactite API: ' . $schema->getLastError(),
                ClientException::INVALID_API_RESPONSE_ERROR
            );
        }
        
        return $data->getData() ?? [];
    }
}
