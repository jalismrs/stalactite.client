<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Customer\Service;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;

/**
 * Trait SystemUnderTestTrait
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Customer
 */
trait SystemUnderTestTrait
{
    /**
     * createSystemUnderTest
     *
     * @param \Jalismrs\Stalactite\Client\Client|null $client
     *
     * @return \Jalismrs\Stalactite\Client\Data\Customer\Service
     */
    private function createSystemUnderTest(
        Client $client = null
    ) : Service {
        $client = $client ?? ClientFactory::createClient();
        
        return new Service($client);
    }
}
