<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\AbstractClient;
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
     * @param AbstractClient $mockClient
     * @param AbstractClient $client1
     * @param AbstractClient $client2
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    private static function checkClients(
        AbstractClient $mockClient,
        AbstractClient $client1,
        AbstractClient $client2
    ) : void {
        self::assertSame($mockClient->getHost(), $client1->getHost());
        self::assertSame($mockClient->getHttpClient(), $client1->getHttpClient());
        self::assertSame($mockClient->getLogger(), $client1->getLogger());
        self::assertSame($mockClient->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
    }
}
