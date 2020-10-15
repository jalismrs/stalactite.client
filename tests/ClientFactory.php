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
     * @return \Jalismrs\Stalactite\Client\Client
     */
    public static function createClient() : Client
    {
        $testClient = new Client('http://fakeHost');
        
        $testClient
            ->setHttpClient(new MockHttpClient())
            ->setLogger(new TestLogger())
            ->setUserAgent('fake user agent');
        
        return $testClient;
    }
}
