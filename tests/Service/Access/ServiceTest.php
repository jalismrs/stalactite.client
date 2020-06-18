<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Service\Access;

use Jalismrs\Stalactite\Client\Access\Service;
use Jalismrs\Stalactite\Client\Tests\Service\AbstractServiceTest;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Service\Access
 */
class ServiceTest extends AbstractServiceTest
{
    public function testCustomer(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->customers();
        $mockService2 = $mockService->customers();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }

    public function testDomain(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->domains();
        $mockService2 = $mockService->domains();

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

    public function testUser(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->users();
        $mockService2 = $mockService->users();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
