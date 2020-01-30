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
     * checkServices
     *
     * @static
     *
     * @param AbstractService $mockService
     * @param AbstractService $mockService1
     * @param AbstractService $mockService2
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    private static function checkServices(
        AbstractService $mockService,
        AbstractService $mockService1,
        AbstractService $mockService2
    ) : void {
        self::assertSame(
            $mockService
                ->getClient()
                ->getHost(),
            $mockService1
                ->getClient()
                ->getHost()
        );
        self::assertSame(
            $mockService
                ->getHttpClient(),
            $mockService1
                ->getHttpClient()
        );
        self::assertSame(
            $mockService
                ->getLogger(),
            $mockService1
                ->getLogger()
        );
        self::assertSame(
            $mockService
                ->getUserAgent(),
            $mockService1
                ->getUserAgent()
        );
        self::assertSame(
            $mockService1,
            $mockService2
        );
    }
}
