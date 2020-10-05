<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client;

use Psr\Log\LoggerInterface;

abstract class AbstractService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->getClient()
            ->getLogger();
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
