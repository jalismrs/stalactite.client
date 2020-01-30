<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Service;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * AbstractServiceTest
 *
 * @package Test
 */
class AbstractServiceTest extends
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
        $host        = 'http://fakeHost';
        $mockClient  = new Client($host);
        $mockService = new Service($mockClient);
        
        self::assertSame(
            $host,
            $mockService
                ->getClient()
                ->getHost()
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
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::assertNull(
            $mockService
                ->getClient()
                ->getUserAgent()
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
        $userAgent   = 'fake user agent';
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockService->setUserAgent($userAgent);
        
        self::assertIsString(
            $mockService
                ->getClient()
                ->getUserAgent()
        );
        self::assertSame(
            $userAgent,
            $mockService
                ->getClient()
                ->getUserAgent()
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
        $httpClient  = new MockHttpClient();
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockService->setHttpClient($httpClient);
        
        self::assertSame($httpClient, $mockService->getHttpClient());
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
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::assertInstanceOf(NullLogger::class, $mockService->getLogger());
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
        $logger      = new TestLogger();
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockService->setLogger($logger);
        
        self::assertSame($logger, $mockService->getLogger());
    }
}
