<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Post;

use Jalismrs\Stalactite\Client\Data\Post\Service;
use Jalismrs\Stalactite\Client\Tests\AbstractTestService;

class ServiceTest extends AbstractTestService
{
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
}
