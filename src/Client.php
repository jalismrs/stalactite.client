<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;
use function array_replace_recursive;
use function json_encode;

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
     * @var Serializer
     */
    private $serializer;
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
    )
    {
        $this->host = $host;
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
     * setHost
     *
     * @param string $host
     *
     * @return $this
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient(): HttpClientInterface
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
    public function setHttpClient(HttpClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * getLogger
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
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
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * getSerializer
     *
     * @return Serializer
     *
     * @throws SerializerException
     */
    public function getSerializer(): Serializer
    {
        if (null === $this->serializer) {
            try {
                $this->serializer = new Serializer();
            } catch (Throwable $throwable) {
                throw new SerializerException(
                    'Error while instantiating the serializer',
                    null,
                    $throwable
                );
            }
        }

        return $this->serializer;
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
     * setUserAgent
     *
     * @param string|null $userAgent
     *
     * @return $this
     */
    public function setUserAgent(?string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /*
     * -------------------------------------------------------------------------
     * default factories -------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * createDefaultHttpClient
     *
     * @return HttpClientInterface
     */
    private function createDefaultHttpClient(): HttpClientInterface
    {
        $headers = ['Content-Type' => 'application/json'];

        if ($this->userAgent) {
            $headers['User-Agent'] = $this->userAgent;
        }

        return HttpClient::create(
            [
                'headers' => $headers,
            ]
        );
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

    /*
     * -------------------------------------------------------------------------
     * API calls ---------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * delete
     *
     * @param string $uri
     * @param array $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    public function delete(
        string $uri,
        array $options,
        JsonSchema $schema
    ): array
    {
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
     * @param string $endpoint
     * @param array $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    public function get(
        string $endpoint,
        array $options,
        JsonSchema $schema
    ): array
    {
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
     * @param string $endpoint
     * @param array $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    public function post(
        string $endpoint,
        array $options,
        JsonSchema $schema
    ): array
    {
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
     * @param string $endpoint
     * @param array $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    public function put(
        string $endpoint,
        array $options,
        JsonSchema $schema
    ): array
    {
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
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    public function request(
        string $method,
        string $endpoint,
        array $options,
        JsonSchema $schema
    ): array
    {
        $url = "{$this->host}{$endpoint}";

        try {
            $this->getLogger()
                ->debug(
                    json_encode(
                        [
                            $method,
                            $url,
                            $options,
                        ],
                        JSON_THROW_ON_ERROR
                    )
                );

            $response = $this
                ->getHttpClient()
                ->request(
                    $method,
                    $url,
                    array_replace_recursive(
                        null === $this->userAgent
                            ? []
                            : [
                            'headers' => [
                                'User-Agent' => $this->userAgent,
                            ],
                        ],
                        $options
                    )
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

        $data = new JsonData();
        try {
            $content = $response->getContent(false);

            $this->getLogger()
                ->debug($content);

            $data->setData($content);
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

        if (!$schema->validate($data)) {
            $exception = new ClientException(
                'Invalid response from Stalactite API: ' . $schema->getLastError(),
                ClientException::INVALID_API_RESPONSE
            );

            $this->getLogger()
                ->error($exception);

            throw $exception;
        }

        $response = $data->getData();
        if (null === $response) {
            $exception = new ClientException(
                'Invalid response from Stalactite API: response is null',
                ClientException::INVALID_API_RESPONSE
            );

            $this->getLogger()
                ->error($exception);

            throw $exception;
        }

        return $response;
    }
}
