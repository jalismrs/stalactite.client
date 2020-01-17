<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test;

use Jalismrs\Stalactite\Client\ClientAbstract;

/**
 * ClientTestTrait
 *
 * @package Jalismrs\Stalactite\Test
 * @mixin \PHPUnit\Framework\TestCase
 */
trait ClientTestTrait
{
    /**
     * checkClients
     *
     * @static
     *
     * @param \Jalismrs\Stalactite\Client\ClientAbstract $baseClient
     * @param \Jalismrs\Stalactite\Client\ClientAbstract $client1
     * @param \Jalismrs\Stalactite\Client\ClientAbstract $client2
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    private static function checkClients(
        ClientAbstract $baseClient,
        ClientAbstract $client1,
        ClientAbstract $client2
    ) : void {
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
    }
}
