<?php
declare(strict_types=1);

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
    protected $host;

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var null|string
     */
    protected $userAgent;

    /**
     * AbstractClient constructor.
     *
     * @param string $host
     * @param string|null $userAgent
     * @param HttpClientInterface|null $httpClient
     */
    public function __construct(
        string $host,
        string $userAgent = null,
        HttpClientInterface $httpClient = null
    )
    {
        $this->host = $host;
        $this->userAgent = $userAgent;

        if (null === $httpClient) {
            $this->httpClient = HttpClient::create(
                array_merge_recursive(
                    [
                        'base_uri' => $host,
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                    ],
                    null === $userAgent
                        ? []
                        : [
                        'headers' => [
                            'User-Agent' => $userAgent,
                        ],
                    ]
                )
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
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * getUserAgent
     *
     * @return null|string
     */
    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    /**
     * delete
     *
     * @param string $url
     * @param array $options
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
    ): array
    {
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
     * @param string $url
     * @param array $options
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
    ): array
    {
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
     * @param string $url
     * @param array $options
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
    ): array
    {
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
     * @param string $url
     * @param array $options
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
    ): array
    {
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
     * @param string $method
     * @param string $url
     * @param array $options
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
    ): array
    {
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
