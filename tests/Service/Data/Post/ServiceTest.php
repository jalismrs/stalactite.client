<?php

namespace Jalismrs\Stalactite\Client\Tests\Service\Data\Post;

use Jalismrs\Stalactite\Client\Data\Post\Service;
use Jalismrs\Stalactite\Client\Tests\Service\AbstractServiceTest;

class ServiceTest extends AbstractServiceTest
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