<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer\Me;

use Jalismrs\Stalactite\Client\Data\Customer\Me\Service;
use Jalismrs\Stalactite\Client\Tests\AbstractTestService;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;

class ServiceTest extends AbstractTestService
{
    public function testAccess(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->access();
        $testService2 = $testService->access();

        self::checkServices(
            $testService,
            $testService1,
            $testService2
        );
    }

    public function testRelation(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->relations();
        $testService2 = $testService->relations();

        self::checkServices(
            $testService,
            $testService1,
            $testService2
        );
    }
}
