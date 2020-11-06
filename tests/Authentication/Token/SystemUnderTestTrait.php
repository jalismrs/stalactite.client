<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Token;

use Jalismrs\Stalactite\Client\Authentication\Token\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;

/**
 * Trait SystemUnderTestTrait
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\Token
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
    ) : Service {
        $client = $client ?? ClientFactory::createClient();
        
        return new Service($client);
    }
}
