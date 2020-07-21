<?php

namespace Jalismrs\Stalactite\Client\Tests\Client;

use Jalismrs\Stalactite\Client\ApiError;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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

        $this->client->expects(self::never())->method('getHttpClient'); // http client not called
        $this->client->expects(self::never())->method('cache'); // cache not set
        $this->client->expects(self::once())->method('getFromCache')->willReturn($cachedResponse); // cache checked

        // get cached value
        $response = $this->client->request($endpoint);
        self::assertSame($cachedResponse, $response->getBody());
    }

    /**
     * @param string $responseBody
     * @param array $options
     * @param bool $shouldCache
     * @throws ClientException
     * @throws InvalidArgumentException
     * @dataProvider getMockHttpClients
     */
    public function testCacheSetOnSuccess(string $responseBody, array $options, bool $shouldCache): void
    {
        $this->client
            ->expects(self::once())
            ->method('getHttpClient')
            ->willReturn(MockHttpClientFactory::create($responseBody, $options));

        $this->client->expects($shouldCache ? self::once() : self::never())->method('cache');

        $this->client->request(new Endpoint('/test'));
    }

    /**
     * @return array|array[]
     * @throws JsonException
     * @throws NormalizerException
     */
    public function getMockHttpClients(): array
    {
        return [
            [
                'test',
                [],
                true
            ], // success
            [
                json_encode(
                    Normalizer::getInstance()->normalize(new ApiError('type', 1, null), [
                        AbstractNormalizer::GROUPS => ['main']
                    ]),
                    JSON_THROW_ON_ERROR, 512
                ),
                ['http_code' => 500],
                false
            ] // error
        ];
    }
}