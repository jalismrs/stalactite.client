<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

use Psr\Log\LoggerInterface;

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
     * getLogger
     *
     * @return LoggerInterface
     */
    public function getLogger() : LoggerInterface
    {
        return $this->getClient()
                    ->getLogger();
    }
}
