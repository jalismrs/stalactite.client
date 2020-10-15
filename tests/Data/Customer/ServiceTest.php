<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer;

use Jalismrs\Stalactite\Client\Data\Customer\Service;
use Jalismrs\Stalactite\Client\Tests\AbstractTestService;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Customer
 */
class ServiceTest extends AbstractTestService
{
    public function testMe(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->me();
        $mockService2 = $mockService->me();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }

    public function testAccess(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->access();
        $mockService2 = $mockService->access();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }

    public function testRelation(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->relations();
        $mockService2 = $mockService->relations();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
