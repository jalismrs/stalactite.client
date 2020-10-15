<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceAbstract
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
abstract class AbstractTestService extends TestCase
{
    /**
     * @param AbstractService $mockService
     * @param AbstractService $mockService1
     * @param AbstractService $mockService2
     */
    final protected static function checkServices(
        AbstractService $mockService,
        AbstractService $mockService1,
        AbstractService $mockService2
    ): void
    {
        self::assertSame(
            $mockService->getClient(),
            $mockService1->getClient()
        );

        self::assertSame(
            $mockService1,
            $mockService2
        );
    }

    final protected static function getMockClient(): Client
    {
        $mockClient = new Client('http://fakeHost');

        $mockClient
            ->setHttpClient(new MockHttpClient())
            ->setLogger(new TestLogger())
            ->setUserAgent('fake user agent');

        return $mockClient;
    }
}
