<?php
declare(strict_types = 1);

namespace Test\Data\Customer;

use Jalismrs\Stalactite\Client\Data\Customer\Client;
use Test\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Test\Data\Customer
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testMe
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testMe() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->me();
        $client2 = $baseClient->me();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
