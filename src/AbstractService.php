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
     * @var Client
     */
    private Client $client;

    /**
     * AbstractService constructor.
     *
     * @param Client $client
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
     * @return LoggerInterface
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
     * @return Client
     *
     * @codeCoverageIgnore
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
