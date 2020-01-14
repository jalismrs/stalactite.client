<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Schema\JsonSchema;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
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
     * @var null|string
     */
    protected $userAgent;
    
    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private $httpClient;
    
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
        
        $this->httpClient = null !== $userAgent
            ? HttpClient::create(
                [
                    'headers' => [
                        'User-Agent' => $this->userAgent,
                    ],
                ]
            )
            : HttpClient::create();
    }
    
    /**
     * getHttpClient
     *
     * @return \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    public function getHttpClient() : HttpClientInterface
    {
        return $this->httpClient;
    }
    
    /**
     * setHttpClient
     *
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient
     *
     * @return $this
     */
    public function setHttpClient(
        HttpClientInterface $httpClient
    ) : self {
        $this->httpClient = $httpClient;
        
        return $this;
    }
    
    /**
     * requestDelete
     *
     * @param string                                     $url
     * @param array                                      $options
     * @param \hunomina\Validator\Json\Schema\JsonSchema $schema
     *
     * @return array
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    final protected function requestDelete(
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
     * requestGet
     *
     * @param string                                     $url
     * @param array                                      $options
     * @param \hunomina\Validator\Json\Schema\JsonSchema $schema
     *
     * @return array
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    final protected function requestGet(
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
     * requestPost
     *
     * @param string                                     $url
     * @param array                                      $options
     * @param \hunomina\Validator\Json\Schema\JsonSchema $schema
     *
     * @return array
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    final protected function requestPost(
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
     * requestPut
     *
     * @param string                                     $url
     * @param array                                      $options
     * @param \hunomina\Validator\Json\Schema\JsonSchema $schema
     *
     * @return array
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    final protected function requestPut(
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
                ->request(
                    $method,
                    $url,
                    $options
                );
        } catch (Throwable $throwable) {
            throw new ClientException(
                'Error while contacting Stalactite API',
                ClientException::CLIENT_TRANSPORT_ERROR,
                $throwable
            );
        }
        
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
