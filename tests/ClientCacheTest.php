<?php

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\ApiError;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
        
        $this->mockClient->setCache($this->mockCache);
    }
    
    /**
     * @throws InvalidArgumentException
     */
    public function testSetCache() : void
    {
        $this->mockCache
            ->expects(self::once())
            ->method('set');
        
        $this->mockClient->cache(
            'GET',
            '/test',
            'test'
        );
    }
    
    /**
     * @throws InvalidArgumentException
     */
    public function testGetFromCache() : void
    {
        $this->mockCache
            ->expects(self::once())
            ->method('get');
    
        $this->mockClient->getFromCache(
            'GET',
            '/test'
        );
    }
    
    /**
     * @param Endpoint $endpoint
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     * @dataProvider getEndpoints
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
     * @return array|array[]
     */
    public function getEndpoints() : array
    {
        return [
            [new Endpoint('/test')],
            [
                new Endpoint(
                    '/test',
                    'GET',
                    false
                ),
            ],
        ];
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
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
     * @param string $responseBody
     * @param array  $options
     * @param bool   $shouldCache
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     * @dataProvider getMockHttpClients
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
     * @return array|array[]
     * @throws JsonException
     * @throws NormalizerException
     */
    public function getMockHttpClients() : array
    {
        return [
            [
                'test',
                [],
                true,
            ],
            // success
            [
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
                ['http_code' => 500],
                false,
            ]
            // error
        ];
    }
}
