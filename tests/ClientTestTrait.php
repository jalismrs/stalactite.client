<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\ClientAbstract;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * ClientTestTrait
 *
 * @package Test
 * @mixin TestCase
 */
trait ClientTestTrait
{
    /**
     * checkClients
     *
     * @static
     *
     * @param ClientAbstract $baseClient
     * @param ClientAbstract $client1
     * @param ClientAbstract $client2
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    private static function checkClients(
        ClientAbstract $baseClient,
        ClientAbstract $client1,
        ClientAbstract $client2
    ): void
    {
        self::assertSame($baseClient->getHost(), $client1->getHost());
        self::assertSame($baseClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($baseClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
    }
}
