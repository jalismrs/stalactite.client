<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement;

use Jalismrs\Stalactite\Client\DataManagement\Client;
use Jalismrs\Stalactite\Test\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ApiGetTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testClientAuthToken
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientAuthToken() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientAuthToken();
        $client2 = $baseClient->getClientAuthToken();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testClientCertificationType
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientCertificationType() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientCertificationType();
        $client2 = $baseClient->getClientCertificationType();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testClientCustomer
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientCustomer() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientCustomer();
        $client2 = $baseClient->getClientCustomer();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testClientDomain
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientDomain() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientDomain();
        $client2 = $baseClient->getClientDomain();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testClientPhoneType
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientPhoneType() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientPhoneType();
        $client2 = $baseClient->getClientPhoneType();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testClientPost
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientPost() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientPost();
        $client2 = $baseClient->getClientPost();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testClientUser
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientUser() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientUser();
        $client2 = $baseClient->getClientUser();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
