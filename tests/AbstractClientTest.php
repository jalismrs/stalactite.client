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
        $client = new Client($host);

        self::assertSame($host, $client->getHost());
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
        $client = new Client('http://fakeHost');

        self::assertNull($client->getUserAgent());

        $userAgent = 'fake user agent';
        $client = new Client(
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
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testHttpClient(): void
    {
        $mockHttpClient = new MockHttpClient();
        $client = new Client('http://fakeHost');
        $client->setHttpClient($mockHttpClient);

        self::assertSame($mockHttpClient, $client->getHttpClient());
    }
}
