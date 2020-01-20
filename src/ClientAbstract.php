<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Schema\JsonSchema;
use InvalidArgumentException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;
use function array_replace_recursive;
use function get_class;
use function gettype;
use function is_array;
use function is_object;

/**
 * ClientAbstract
 *
 * @package Jalismrs\Stalactite\Client
 */
abstract class ClientAbstract
{
    /**
     * @var string
     */
    protected $host;
    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    protected $httpClient;
    /**
     * @var null|string
     */
    protected $userAgent;
    
    /**
     * ClientAbstract constructor.
     *
     * @param string                                                 $host
     * @param string|null                                            $userAgent
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface|null $httpClient
     */
    public function __construct(
        string $host,
        string $userAgent = null,
        HttpClientInterface $httpClient = null
    ) {
        $this->host      = $host;
        $this->userAgent = $userAgent;
        
        if (null === $httpClient) {
            $this->httpClient = HttpClient::create(
                null === $userAgent
                    ? []
                    : [
                    'headers' => [
                        'User-Agent' => $userAgent,
                    ],
                ]
            );
        } else {
            $this->httpClient = $httpClient;
        }
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
     * getHttpClient
     *
     * @return \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    public function getHttpClient() : HttpClientInterface
    {
        return $this->httpClient;
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
     * requestDelete
     *
     * @param string                                     $url
     * @param array                                      $options
     * @param \hunomina\Validator\Json\Schema\JsonSchema $schema
     *
     * @return array
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \Jalismrs\Stalactite\Client\ClientException
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
     * @throws \Jalismrs\Stalactite\Client\ClientException
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
     * @throws \Jalismrs\Stalactite\Client\ClientException
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
     * @throws \Jalismrs\Stalactite\Client\ClientException
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
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    private function request(
        string $method,
        string $url,
        array $options,
        JsonSchema $schema
    ) : array {
        try {
            $response = $this
                ->httpClient
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
        
        $response = $data->getData();
        if (null === $response) {
            throw new ClientException(
                'Invalid response from Stalactite API: response is null',
                ClientException::INVALID_API_RESPONSE_ERROR
            );
        }
        
        return $response;
    }
}
