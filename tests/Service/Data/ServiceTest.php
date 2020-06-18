<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Service\Data;

use Jalismrs\Stalactite\Client\Data\Service;
use Jalismrs\Stalactite\Client\Tests\Service\AbstractServiceTest;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Service\Data
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

    public function testPost(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->posts();
        $mockService2 = $mockService->posts();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }

    public function testPermission(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->permissions();
        $mockService2 = $mockService->permissions();

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
