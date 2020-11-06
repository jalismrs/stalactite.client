<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer\Me\Relation;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Customer\Me\Relation\Service;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;

/**
 * Trait SystemUnderTestTrait
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Customer\Me\Relation
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
