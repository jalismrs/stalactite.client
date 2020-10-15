<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Service;
use Jalismrs\Stalactite\Client\Tests\AbstractTestService;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication
 */
class ServiceTest extends AbstractTestService
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
