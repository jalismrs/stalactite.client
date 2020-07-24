<?php

namespace Jalismrs\Stalactite\Client\Tests\Service\Data\User\Me;

use Jalismrs\Stalactite\Client\Data\User\Me\Service;
use Jalismrs\Stalactite\Client\Tests\Service\AbstractServiceTest;

class ServiceTest extends AbstractServiceTest
{
    public function testLead(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->leads();
        $mockService2 = $mockService->leads();

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

    public function testSubordinate(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->subordinates();
        $mockService2 = $mockService->subordinates();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}