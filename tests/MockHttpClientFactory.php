<?php

namespace Jalismrs\Stalactite\Client\Tests;

use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * MockHttpClientFactory
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
class MockHttpClientFactory
{
    /**
     * create
     *
     * @static
     *
     * @param string $body
     *
     * @return MockHttpClient
     */
    public static function create(string $body) : MockHttpClient
    {
        return new MockHttpClient(
            [
                new MockResponse($body),
            ],
            'http://fakeHost'
        );
    }
}
