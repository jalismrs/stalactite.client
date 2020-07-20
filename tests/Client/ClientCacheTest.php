<?php

namespace Jalismrs\Stalactite\Client\Tests\Client;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class ClientCacheTest extends TestCase
{
    /**
     * @var Client|MockObject $client
     */
    private MockObject $client;

    /**
     * @var MockObject|CacheInterface $cache
     */
    private MockObject $cache;

    public function setUp(): void
    {
        $this->cache = $this->createMock(CacheInterface::class);

        $this->client = $this->getMockBuilder(Client::class)
            ->onlyMethods([
                'cache',
                'getFromCache',
                'getHttpClient'
            ])
            ->setConstructorArgs(['http://fakeHost'])
            ->getMock();

        $this->client->setCache($this->cache);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSetCache(): void
    {
        $client = new Client('http://fakeHost');
        $client->setCache($this->cache);

        $this->cache->expects(self::once())->method('set');
        $client->cache('GET', '/test', 'test');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetFromCache(): void
    {
        $client = new Client('http://fakeHost');
        $client->setCache($this->cache);

        $this->cache->expects(self::once())->method('get');
        $client->getFromCache('GET', '/test');
    }

    /**
     * @param Endpoint $endpoint
     * @throws ClientException
     * @throws InvalidArgumentException
     * @dataProvider getEndpoints
     */
    public function testEndpointCache(Endpoint $endpoint): void
    {
        $this->client->expects(self::once())->method('getFromCache')->willReturn(null);
        $this->client->expects(self::once())->method('getHttpClient')->willReturn(MockHttpClientFactory::create('test'));
        $this->client->expects($endpoint->isCacheable() ? self::once() : self::never())->method('cache');

        $this->client->request($endpoint);
    }

    /**
     * @return array|array[]
     */
    public function getEndpoints(): array
    {
        return [
            [new Endpoint('/test')],
            [new Endpoint('/test', 'GET', false)]
        ];
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testCacheHit(): void
    {
        $endpoint = new Endpoint('/test'); // cacheable
        $cachedResponse = 'test';

        $this->client->expects(self::never())->method('getHttpClient'); // http client no called
        $this->client->expects(self::never())->method('cache'); // nothing cached
        $this->client->expects(self::once())->method('getFromCache')->willReturn($cachedResponse); // cache checked

        // get cached value
        $response = $this->client->request($endpoint);
        self::assertSame($cachedResponse, $response->getBody());
    }
}