<?php
declare(strict_types = 1);

namespace Test\Data\AuthToken;

use Jalismrs\Stalactite\Client\Data\AuthToken\Client;
use Test\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Test\Data\AuthToken
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testCustomer
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCustomer() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->customer();
        $client2 = $baseClient->customer();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testDomain
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testDomain() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->domain();
        $client2 = $baseClient->domain();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testPost
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPost() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->post();
        $client2 = $baseClient->post();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testUser
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testUser() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->user();
        $client2 = $baseClient->user();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
