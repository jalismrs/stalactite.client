<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test;

use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test
 */
class ClientTest extends
    TestCase
{
    /**
     * testAccessManagement
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAccessManagement() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->accessManagement();
        $client2 = $baseClient->accessManagement();
        
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
    }
    
    /**
     * testAuth
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAuth() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->authentification();
        $client2 = $baseClient->authentification();
        
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
    }
    
    /**
     * testDataManagement
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testDataManagement() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->dataManagement();
        $client2 = $baseClient->dataManagement();
        
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
    }
}
