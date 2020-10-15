<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Service;
use Jalismrs\Stalactite\Client\Tests\AbstractTestService;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication
 */
class ServiceTest extends AbstractTestService
{
    public function testTrustedApp(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->clientApps();
        $testService2 = $testService->clientApps();

        self::checkServices(
            $testService,
            $testService1,
            $testService2
        );
    }
}
