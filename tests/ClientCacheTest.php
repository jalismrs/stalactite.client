<?php

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\ApiError;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class ClientCacheTest
 *
 * @package Jalismrs\Stalactite\Client\Tests
 *
 * @covers \Jalismrs\Stalactite\Client\Client
 */
class ClientCacheTest extends
    TestCase
{
    /**
     * mockCache
     *
     * @var \PHPUnit\Framework\MockObject\MockObject|\Psr\SimpleCache\CacheInterface
     */
    private MockObject $mockCache;
    /**
     * mockClient
     *
     * @var \PHPUnit\Framework\MockObject\MockObject|\Jalismrs\Stalactite\Client\Client
     */
    private MockObject $mockClient;
    /**
     * testClient
     *
     * @var \Jalismrs\Stalactite\Client\Client
     */
    private Client $testClient;
    
    /**
     * testSetCache
     *
     * @return void
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testSetCache() : void
    {
        $this->mockCache
            ->expects(self::once())
            ->method('set');
        
        $this->testClient->cache(
            'GET',
            '/test',
            'test'
        );
    }
    
    /**
     * testGetFromCache
     *
     * @return void
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testGetFromCache() : void
    {
        $this->mockCache
            ->expects(self::once())
            ->method('get');
        
        $this->testClient->getFromCache(
            'GET',
            '/test'
        );
    }
    
    /**
     * testEndpointCache
     *
     * @param \Jalismrs\Stalactite\Client\Util\Endpoint $endpoint
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @dataProvider provideEndpointCache
     */
    public function testEndpointCache(Endpoint $endpoint) : void
    {
        $this->mockClient
            ->expects(self::once())
            ->method('getFromCache')
            ->willReturn(null);
        $this->mockClient
            ->expects(self::once())
            ->method('getHttpClient')
            ->willReturn(MockHttpClientFactory::create('test'));
        $this->mockClient
            ->expects(
                $endpoint->isCacheable()
                    ? self::once()
                    : self::never()
            )
            ->method('cache');
        
        $this->mockClient->request($endpoint);
    }
    
    /**
     * provideEndpointCache
     *
     * @return \Jalismrs\Stalactite\Client\Util\Endpoint[][]
     */
    public function provideEndpointCache() : array
    {
        return [
            'cacheable'     => [
                new Endpoint(
                    '/test'
                ),
            ],
            'not cacheable' => [
                new Endpoint(
                    '/test',
                    'GET',
                    false
                ),
            ],
        ];
    }
    
    /**
     * testCacheHit
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testCacheHit() : void
    {
        $endpoint       = new Endpoint('/test'); // cacheable
        $cachedResponse = 'test';
        
        $this->mockClient
            ->expects(self::never())
            ->method('getHttpClient');     // http client not called
        $this->mockClient
            ->expects(self::never())
            ->method('cache');             // cache not set
        $this->mockClient
            ->expects(self::once())
            ->method('getFromCache')
            ->willReturn($cachedResponse); // cache checked
        
        // get cached value
        $response = $this->mockClient->request($endpoint);
        self::assertSame(
            $cachedResponse,
            $response->getBody()
        );
    }
    
    /**
     * testCacheSetOnSuccess
     *
     * @param string $responseBody
     * @param array  $options
     * @param bool   $shouldCache
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @dataProvider provideCacheSetOnSuccess
     */
    public function testCacheSetOnSuccess(
        string $responseBody,
        array $options,
        bool $shouldCache
    ) : void {
        $this->mockClient
            ->expects(self::once())
            ->method('getHttpClient')
            ->willReturn(
                MockHttpClientFactory::create(
                    $responseBody,
                    $options
                )
            );
        
        $this->mockClient
            ->expects(
                $shouldCache
                    ? self::once()
                    : self::never()
            )
            ->method('cache');
        
        $this->mockClient->request(new Endpoint('/test'));
    }
    
    /**
     * provideCacheSetOnSuccess
     *
     * @return array[]
     *
     * @throws \Jalismrs\Stalactite\Client\Exception\NormalizerException
     * @throws \JsonException
     */
    public function provideCacheSetOnSuccess() : array
    {
        return [
            'success' => [
                'test',
                [],
                true,
            ],
            'error'   => [
                json_encode(
                    Normalizer::getInstance()
                              ->normalize(
                                  new ApiError(
                                      'type',
                                      1,
                                      null
                                  ),
                                  [
                                      AbstractNormalizer::GROUPS => ['main'],
                                  ]
                              ),
                    JSON_THROW_ON_ERROR,
                ),
                [
                    'http_code' => 500,
                ],
                false,
            ],
        ];
    }
    
    /**
     * setUp
     *
     * @return void
     */
    protected function setUp() : void
    {
        $this->mockCache = $this->createMock(CacheInterface::class);
        
        $this->mockClient = $this
            ->getMockBuilder(Client::class)
            ->onlyMethods(
                [
                    'cache',
                    'getFromCache',
                    'getHttpClient',
                ]
            )
            ->setConstructorArgs(['http://fakeHost'])
            ->getMock();
        $this->testClient = ClientFactory::createClient();
        
        $this->mockClient->setCache($this->mockCache);
        $this->testClient->setCache($this->mockCache);
    }
}
