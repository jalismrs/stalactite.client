<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\AbstractService;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTestService
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
abstract class AbstractTestService extends
    TestCase
{
    /**
     * checkServices
     *
     * @static
     *
     * @param AbstractService $parentService
     * @param AbstractService $service1
     * @param AbstractService $service2
     *
     * @return void
     */
    final protected static function checkServices(
        AbstractService $parentService,
        AbstractService $service1,
        AbstractService $service2
    ) : void {
        self::assertSame(
            $parentService->getClient(),
            $service1->getClient()
        );
        
        self::assertSame(
            $service1,
            $service2
        );
    }
}
