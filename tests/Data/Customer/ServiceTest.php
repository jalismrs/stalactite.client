<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer;

use Jalismrs\Stalactite\Client\Data\Customer\Service;
use Jalismrs\Stalactite\Client\Tests\AbstractTestService;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Customer
 */
class ServiceTest extends AbstractTestService
{
    public function testMe(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->me();
        $testService2 = $testService->me();

        self::checkServices(
            $testService,
            $testService1,
            $testService2
        );
    }

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
