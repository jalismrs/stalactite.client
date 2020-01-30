<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Schema\JsonSchema;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * AbstractService
 *
 * @package Jalismrs\Stalactite\Service
 */
abstract class AbstractService
{
    /**
     * @var Client
     */
    private $client;
    
    /**
     * AbstractService constructor.
     *
     * @param Client $client
     */
    public function __construct(
        Client $client
    ) {
        $this->client = $client;
    }
    
    /**
     * getClient
     *
     * @return Client
     */
    public function getClient() : Client
    {
        return $this->client;
    }
    
    /**
     * setClient
     *
     * @param Client $client
     *
     * @return $this
     */
    public function setClient(Client $client) : self
    {
        $this->client = $client;
        
        return $this;
    }
    
    /**
     * @return HttpClientInterface
     */
    public function getHttpClient() : HttpClientInterface
    {
        return $this->getClient()
                    ->getHttpClient();
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
        $this->getClient()
             ->setHttpClient($httpClient);
        
        return $this;
    }
    
    /**
     * getLogger
     *
     * @return LoggerInterface
     */
    public function getLogger() : LoggerInterface
    {
        return $this->getClient()
                    ->getLogger();
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
        $this->getClient()
             ->setLogger($logger);
        
        return $this;
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
        $this->getClient()
             ->setUserAgent($userAgent);
        
        return $this;
    }
    
    /*
     * -------------------------------------------------------------------------
     * API calls ---------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    /**
     * delete
     *
     * @param string     $uri
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    final protected function delete(
        string $uri,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this
            ->getClient()
            ->delete(
                $uri,
                $options,
                $schema
            );
    }
    
    /**
     * get
     *
     * @param string     $endpoint
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    final protected function get(
        string $endpoint,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this
            ->getClient()
            ->get(
                $endpoint,
                $options,
                $schema
            );
    }
    
    /**
     * post
     *
     * @param string     $endpoint
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    final protected function post(
        string $endpoint,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this
            ->getClient()
            ->post(
                $endpoint,
                $options,
                $schema
            );
    }
    
    /**
     * put
     *
     * @param string     $endpoint
     * @param array      $options
     * @param JsonSchema $schema
     *
     * @return array
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     */
    final protected function put(
        string $endpoint,
        array $options,
        JsonSchema $schema
    ) : array {
        return $this
            ->getClient()
            ->put(
                $endpoint,
                $options,
                $schema
            );
    }
}
