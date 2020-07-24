<?php

namespace Jalismrs\Stalactite\Client\Tests\Service\Data\Domain;

use Jalismrs\Stalactite\Client\Data\Domain\Service;
use Jalismrs\Stalactite\Client\Tests\Service\AbstractServiceTest;

class ServiceTest extends AbstractServiceTest
{
    public function testLead(): void
    {
        $mockService = new Service(self::getMockClient());
        $mockService1 = $mockService->relations();
        $mockService2 = $mockService->relations();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}