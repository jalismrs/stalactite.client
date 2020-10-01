<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Service\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Service;
use Jalismrs\Stalactite\Client\Tests\Service\AbstractServiceTest;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Service\Authentication
 */
class ServiceTest extends AbstractServiceTest
{
    public function testTrustedApp(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->clientApps();
        $mockService2 = $mockService->clientApps();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
