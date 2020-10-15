<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Service;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
class ServiceTest extends AbstractTestService
{
    public function testAuthentication(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        
        $testService1 = $testService->authentication();
        $testService2 = $testService->authentication();

        self::checkServices(
            $testService,
            $testService1,
            $testService2
        );
    }

    public function testData(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->data();
        $testService2 = $testService->data();

        self::checkServices(
            $testService,
            $testService1,
            $testService2
        );
    }
}
