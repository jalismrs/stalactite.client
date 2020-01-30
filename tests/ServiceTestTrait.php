<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\AbstractService;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * ServiceTestTrait
 *
 * @package Test
 * @mixin TestCase
 */
trait ServiceTestTrait
{
    /**
     * checkClients
     *
     * @static
     *
     * @param AbstractService $mockService
     * @param AbstractService $client1
     * @param AbstractService $client2
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    private static function checkClients(
        AbstractService $mockService,
        AbstractService $client1,
        AbstractService $client2
    ) : void {
        self::assertSame($mockService->getHost(), $client1->getHost());
        self::assertSame($mockService->getHttpClient(), $client1->getHttpClient());
        self::assertSame($mockService->getLogger(), $client1->getLogger());
        self::assertSame($mockService->getUserAgent(), $client1->getUserAgent());
        self::assertSame($client1, $client2);
    }
}
