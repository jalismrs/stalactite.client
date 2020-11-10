<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Util\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTestEndpoint
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
abstract class AbstractTestEndpoint extends TestCase
{
    /**
     * testRequestMethodCalledOnce
     *
     * @return void
     */
    abstract public function testRequestMethodCalledOnce(): void;

    /**
     * createMockClient
     *
     * @param bool $requestCalled
     *
     * @return Client
     */
    final protected function createMockClient(bool $requestCalled = true): Client
    {
        $mockClient = $this->createMock(Client::class);

        $mockClient
            ->expects(
                $requestCalled
                    ? self::once()
                    : self::never()
            )
            ->method('request');

        return $mockClient;
    }

    final protected static function checkPaginatedResponse(Response $response, string $expectedType): void
    {
        $body = $response->getBody();

        self::assertIsArray($body);

        self::assertArrayHasKey('_metas', $body);
        self::checkMetas($body['_metas']);

        self::assertArrayHasKey('results', $body);
        self::checkResults($body['results'], $expectedType);
    }

    final protected static function checkMetas($metas): void
    {
        self::assertIsArray($metas);

        self::assertArrayHasKey('pageSize', $metas);
        self::assertIsInt($metas['pageSize']);

        self::assertArrayHasKey('total', $metas);
        self::assertIsInt($metas['total']);
    }

    final protected static function checkResults($results, string $expectedType): void
    {
        self::assertIsArray($results);
        self::assertContainsOnlyInstancesOf($expectedType, $results);
    }
}
