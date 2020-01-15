<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement;

use Jalismrs\Stalactite\Client\DataManagement\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement
 */
class ClientTest extends
    TestCase
{
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
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->user();
        $client2 = $baseClient->user();
        
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
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
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->customer();
        $client2 = $baseClient->customer();
        
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
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
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->domain();
        $client2 = $baseClient->domain();
        
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
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
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->post();
        $client2 = $baseClient->post();
        
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
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
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->certificationType();
        $client2 = $baseClient->certificationType();
        
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
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
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->phoneType();
        $client2 = $baseClient->phoneType();
        
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
    }
    
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
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->authToken();
        $client2 = $baseClient->authToken();
        
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
    }
}
