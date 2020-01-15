<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test;

use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientAbstractTest
 *
 * @package Jalismrs\Stalactite\Test
 */
class ClientAbstractTest extends
    TestCase
{
    /**
     * testGetHost
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetHost() : void
    {
        $host   = 'http://fakeClient';
        $client = new Client(
            $host
        );
        
        self::assertSame($host, $client->getHost());
    }
    
    /**
     * testGetUserAgent
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetUserAgent() : void
    {
        $client = new Client(
            'http://fakeClient'
        );
        
        self::assertNull($client->getUserAgent());
        
        $userAgent = 'fake user agent';
        $client    = new Client(
            'http://fakeClient',
            $userAgent
        );
        
        self::assertIsString($client->getUserAgent());
        self::assertSame($userAgent, $client->getUserAgent());
    }
    
    /**
     * testGetHttpClient
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetHttpClient() : void
    {
        $mockHttpClient = new MockHttpClient();
        $client         = new Client(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        self::assertSame($mockHttpClient, $client->getHttpClient());
    }
}
