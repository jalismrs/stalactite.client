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
