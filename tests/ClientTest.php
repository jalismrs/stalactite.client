<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test;

use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ApiGetTest
 *
 * @package Jalismrs\Stalactite\Test
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testClientAccessManagement
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientAccessManagement() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientAccessManagement();
        $client2 = $baseClient->getClientAccessManagement();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testClientAuthentification
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientAuthentification() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientAuthentification();
        $client2 = $baseClient->getClientAuthentification();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testClientDataManagement
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientDataManagement() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientDataManagement();
        $client2 = $baseClient->getClientDataManagement();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
