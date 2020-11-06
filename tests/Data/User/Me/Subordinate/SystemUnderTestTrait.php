<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User\Me\Subordinate;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\User\Me\Subordinate\Service;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;

/**
 * Trait SystemUnderTestTrait
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\User\Me\Subordinate
 */
trait SystemUnderTestTrait
{
    /**
     * createSystemUnderTest
     *
     * @param Client|null $client
     *
     * @return Service
     */
    private function createSystemUnderTest(
        Client $client = null
    ): Service
    {
        $client = $client ?? ClientFactory::createClient();

        return new Service($client);
    }
}
