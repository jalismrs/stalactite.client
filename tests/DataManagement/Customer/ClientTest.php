<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement\Customer;

use Jalismrs\Stalactite\Client\DataManagement\Customer\Client;
use Jalismrs\Stalactite\Test\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement\Customer
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testClientMe
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientMe() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientMe();
        $client2 = $baseClient->getClientMe();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
