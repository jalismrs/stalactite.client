<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test;

use InvalidArgumentException;
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
     * testHost
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testHost() : void
    {
        $host   = 'http://fakeClient';
        $client = new Client(
            $host
        );
        
        self::assertSame($host, $client->getHost());
    }
    
    /**
     * testUserAgent
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testUserAgent() : void
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
     * testHttpClient
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testHttpClient() : void
    {
        $mockHttpClient = new MockHttpClient();
        $client         = new Client(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        self::assertSame($mockHttpClient, $client->getHttpClient());
    }
    
    /**
     * testInvalidHttpClient
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function testInvalidHttpClient() : void
    {
        $this->expectException(InvalidArgumentException::class);
        
        new Client(
            'http://fakeClient',
            null,
            false
        );
    }
}
