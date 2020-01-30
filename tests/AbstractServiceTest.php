<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

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
        $mockService = new Service($host);
        
        self::assertSame($host, $mockService->getHost());
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
        $mockService = new Service('http://fakeHost');
        
        self::assertNull($mockService->getUserAgent());
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
        $mockService = new Service('http://fakeHost');
        $mockService->setUserAgent($userAgent);
        
        self::assertIsString($mockService->getUserAgent());
        self::assertSame($userAgent, $mockService->getUserAgent());
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
        $mockService = new Service('http://fakeHost');
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
        $mockService = new Service('http://fakeHost');
        
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
        $mockService = new Service('http://fakeHost');
        $mockService->setLogger($logger);
        
        self::assertSame($logger, $mockService->getLogger());
    }
}
