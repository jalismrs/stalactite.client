<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
class ClientTest extends
    TestCase
{
    /**
     * testHost
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testHost() : void
    {
        $host       = 'http://fakeHost';
        $mockClient = new Client($host);
        
        self::assertSame(
            $host,
            $mockClient->getHost()
        );
    }
    
    /**
     * testUserAgent
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testDefaultUserAgent() : void
    {
        $mockClient = new Client('http://fakeHost');
        
        self::assertNull(
            $mockClient->getUserAgent()
        );
    }
    
    /**
     * testUserAgent
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testUserAgent() : void
    {
        $userAgent  = 'fake user agent';
        $mockClient = new Client('http://fakeHost');
        $mockClient->setUserAgent($userAgent);
        
        self::assertIsString(
            $mockClient->getUserAgent()
        );
        self::assertSame(
            $userAgent,
            $mockClient->getUserAgent()
        );
    }
    
    /**
     * testHttpClient
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testHttpClient() : void
    {
        $httpClient = new MockHttpClient();
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient($httpClient);
        
        self::assertSame(
            $httpClient,
            $mockClient->getHttpClient()
        );
    }
    
    /**
     * testDefaultLogger
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testDefaultLogger() : void
    {
        $mockClient = new Client('http://fakeHost');
        
        self::assertInstanceOf(
            NullLogger::class,
            $mockClient->getLogger()
        );
    }
    
    /**
     * testLogger
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testLogger() : void
    {
        $logger     = new TestLogger();
        $mockClient = new Client('http://fakeHost');
        $mockClient->setLogger($logger);
        
        self::assertSame(
            $logger,
            $mockClient->getLogger()
        );
    }
}
