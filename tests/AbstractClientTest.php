<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * AbstractClientTest
 *
 * @package Test
 */
class AbstractClientTest extends
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
    public function testHost(): void
    {
        $host = 'http://fakeHost';
        $mockClient = new Client($host);

        self::assertSame($host, $mockClient->getHost());
    }

    /**
     * testUserAgent
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testUserAgent(): void
    {
        $mockClient = new Client('http://fakeHost');

        self::assertNull($mockClient->getUserAgent());

        $userAgent = 'fake user agent';
        $mockClient = new Client('http://fakeHost');
        $mockClient->setUserAgent($userAgent);

        self::assertIsString($mockClient->getUserAgent());
        self::assertSame($userAgent, $mockClient->getUserAgent());
    }

    /**
     * testHttpClient
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testHttpClient(): void
    {
        $mockHttpClient = new MockHttpClient();
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient($mockHttpClient);

        self::assertSame($mockHttpClient, $mockClient->getHttpClient());
    }
}
