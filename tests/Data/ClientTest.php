<?php
declare(strict_types = 1);

namespace Test\Data;

use Jalismrs\Stalactite\Client\Data\Client;
use Test\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Test\Data
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testAuthToken
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAuthToken() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->authToken();
        $client2 = $baseClient->authToken();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testCertificationType
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCertificationType() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->certificationType();
        $client2 = $baseClient->certificationType();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testCustomer
     *
     * @return void
     *
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
     * testPhoneType
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPhoneType() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->phoneType();
        $client2 = $baseClient->phoneType();
        
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
