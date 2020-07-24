<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Service;

use Jalismrs\Stalactite\Client\Service;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Service
 */
class ServiceTest extends AbstractServiceTest
{
    public function testAuthentication(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->authentication();
        $mockService2 = $mockService->authentication();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }

    public function testData(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->data();
        $mockService2 = $mockService->data();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
