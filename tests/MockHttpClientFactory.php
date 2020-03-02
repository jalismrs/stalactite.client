<?php
declare(strict_types=1);

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
    public static function create(string $responseBody, array $infos = []): MockHttpClient
    {
        return new MockHttpClient(
            [
                new MockResponse($responseBody, $infos)
            ],
            'http://fakeHost'
        );
    }
}
