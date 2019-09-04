<?php

namespace jalismrs\Stalactite\Client;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Schema\JsonSchema;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

abstract class AbstractClient
{
    /** @var string $apiHost */
    protected $apiHost;

    /** @var string $userAgent */
    protected $userAgent;

    /** @var HttpClientInterface $httpClient */
    protected $httpClient;

    /**
     * AbstractClient constructor.
     * @param string $apiHost
     * @param string|null $userAgent Can be used to identify the application using the client
     */
    public function __construct(string $apiHost, ?string $userAgent = null)
    {
        $this->apiHost = $apiHost;
        $this->userAgent = $userAgent;
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient(): HttpClientInterface
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
     * @param HttpClientInterface $httpClient
     * @return AbstractClient
     * Allow mock
     */
    public function setHttpClient(HttpClientInterface $httpClient): AbstractClient
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @param JsonSchema $schema
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * Method to contact the Stalactite API, check the response based on a JsonSchema and then return the response as an array
     */
    protected function request(string $method, string $url, array $options, JsonSchema $schema): array
    {
        try {
            $response = $this->getHttpClient()->request($method, $url, $options);
        } catch (Throwable $t) {
            throw new ClientException('Error while contacting Stalactite API', ClientException::CLIENT_TRANSPORT_ERROR);
        }

        $data = new JsonData();
        try {
            $data->setData($response->getContent());
        } catch (Throwable $t) {
            throw new ClientException('Invalid json response from Stalactite API', ClientException::INVALID_API_RESPONSE_ERROR);
        }

        if (!$schema->validate($data)) {
            throw new ClientException('Invalid response from Stalactite API: ' . $schema->getLastError(), ClientException::INVALID_API_RESPONSE_ERROR);
        }

        return $data->getData() ?? [];
    }
}