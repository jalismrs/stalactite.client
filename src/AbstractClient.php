<?php

namespace jalismrs\Stalactite\Client;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
}