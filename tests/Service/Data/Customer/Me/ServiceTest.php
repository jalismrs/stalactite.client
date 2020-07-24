<?php

namespace Jalismrs\Stalactite\Client\Tests\Service\Data\Customer\Me;

use Jalismrs\Stalactite\Client\Data\Customer\Me\Service;
use Jalismrs\Stalactite\Client\Tests\Service\AbstractServiceTest;

class ServiceTest extends AbstractServiceTest
{
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