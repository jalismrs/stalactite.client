<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Domain;

use Jalismrs\Stalactite\Client\Data\Domain\Service;
use Jalismrs\Stalactite\Client\Tests\AbstractTestService;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;

class ServiceTest extends AbstractTestService
{
    public function testLead(): void
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
