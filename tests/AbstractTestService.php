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
     * @param AbstractService $testService
     * @param AbstractService $testService1
     * @param AbstractService $testService2
     */
    final protected static function checkServices(
        AbstractService $testService,
        AbstractService $testService1,
        AbstractService $testService2
    ) : void {
        self::assertSame(
            $testService->getClient(),
            $testService1->getClient()
        );
        
        self::assertSame(
            $testService1,
            $testService2
        );
    }
}
