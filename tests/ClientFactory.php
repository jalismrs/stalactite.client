<?php

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Client;
use Psr\Log\Test\TestLogger;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * Class ClientFactory
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
class ClientFactory
{
    /**
     * createClient
     *
     * @static
     * @return Client
     */
    public static function createClient() : Client
    {
        $testClient = self::createBasicClient();
        
        $testClient
            ->setHttpClient(new MockHttpClient())
            ->setLogger(new TestLogger())
            ->setUserAgent('fake user agent');
        
        return $testClient;
    }
    
    /**
     * createBasicClient
     *
     * @static
     * @return Client
     */
    public static function createBasicClient(): Client
    {
        return new Client('http://fakeHost');
    }
}
