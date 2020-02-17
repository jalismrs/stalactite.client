<?php
declare(strict_types=1);

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
    )
    {
        $this->client = $client;
    }

    /**
     * getLogger
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->getClient()
            ->getLogger();
    }

    /**
     * getClient
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
