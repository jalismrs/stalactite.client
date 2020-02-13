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
     * testGetHost
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetHost() : void
    {
        $host       = 'http://fakeHost';
        $mockClient = new Client($host);
        
        self::assertSame(
            $host,
            $mockClient->getHost()
        );
    }
    
    /**
     * testGetUserAgentDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetUserAgentDefault() : void
    {
        $mockClient = new Client('http://fakeHost');
        
        self::assertNull($mockClient->getUserAgent());
    }
    
    /**
     * testGetUserAgent
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetUserAgent() : void
    {
        $userAgent  = 'fake user agent';
        $mockClient = new Client('http://fakeHost');
        $mockClient->setUserAgent($userAgent);
        
        self::assertSame(
            $userAgent,
            $mockClient->getUserAgent()
        );
    }
    
    /**
     * testGetHttpClient
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetHttpClient() : void
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
     * testGetLogger
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetLogger() : void
    {
        $logger     = new TestLogger();
        $mockClient = new Client('http://fakeHost');
        $mockClient->setLogger($logger);
        
        self::assertSame(
            $logger,
            $mockClient->getLogger()
        );
    }
    
    /**
     * testGetLoggerDefault
     *
     * @return void
     *
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetLoggerDefault() : void
    {
        $mockClient = new Client('http://fakeHost');
        
        self::assertInstanceOf(
            NullLogger::class,
            $mockClient->getLogger()
        );
    }
}
