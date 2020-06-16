<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Service\Access\User;

use Jalismrs\Stalactite\Client\Access\User\Service;
use Jalismrs\Stalactite\Client\Tests\Service\AbstractServiceTest;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Service\Access\User
 */
class ServiceTest extends AbstractServiceTest
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
}
