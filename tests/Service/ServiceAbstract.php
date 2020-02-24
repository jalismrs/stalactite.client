<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Service;

use Jalismrs\Stalactite\Client\AbstractService;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * ServiceAbstract
 *
 * @package Jalismrs\Stalactite\Client\Tests\Service
 */
abstract class ServiceAbstract extends
    TestCase
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
    final protected static function checkServices(
        AbstractService $mockService,
        AbstractService $mockService1,
        AbstractService $mockService2
    ) : void {
        self::assertSame(
            $mockService->getClient(),
            $mockService1->getClient()
        );
        
        self::assertSame(
            $mockService1,
            $mockService2
        );
    }
}
