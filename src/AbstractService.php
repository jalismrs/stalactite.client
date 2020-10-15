<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client;

use Psr\Log\LoggerInterface;

/**
 * Class AbstractService
 *
 * @package Jalismrs\Stalactite\Client
 */
abstract class AbstractService
{
    /**
     * client
     *
     * @var \Jalismrs\Stalactite\Client\Client
     */
    private Client $client;
    
    /**
     * AbstractService constructor.
     *
     * @param \Jalismrs\Stalactite\Client\Client $client
     *
     * @codeCoverageIgnore
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * getLogger
     *
     * @return \Psr\Log\LoggerInterface
     *
     * @codeCoverageIgnore
     */
    public function getLogger(): LoggerInterface
    {
        return $this->getClient()
            ->getLogger();
    }
    
    /**
     * getClient
     *
     * @return \Jalismrs\Stalactite\Client\Client
     *
     * @codeCoverageIgnore
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
