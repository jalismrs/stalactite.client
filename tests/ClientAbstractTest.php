<?php
declare(strict_types = 1);

namespace Test;

use InvalidArgumentException;
use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientAbstractTest
 *
 * @package Test
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
        $host   = 'http://fakeHost';
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
            'http://fakeHost'
        );
        
        self::assertNull($client->getUserAgent());
        
        $userAgent = 'fake user agent';
        $client    = new Client(
            'http://fakeHost',
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
            'http://fakeHost',
            null,
            $mockHttpClient
        );
        
        self::assertSame($mockHttpClient, $client->getHttpClient());
    }
}
