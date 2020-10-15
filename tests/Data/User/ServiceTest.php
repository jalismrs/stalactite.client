<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User;

use Jalismrs\Stalactite\Client\Data\User\Service;
use Jalismrs\Stalactite\Client\Tests\AbstractTestService;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;

class ServiceTest extends AbstractTestService
{
    public function testLead(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->leads();
        $testService2 = $testService->leads();

        self::checkServices(
            $testService,
            $testService1,
            $testService2
        );
    }

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

    public function testPost(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->posts();
        $testService2 = $testService->posts();

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

    public function testSubordinate(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->subordinates();
        $testService2 = $testService->subordinates();

        self::checkServices(
            $testService,
            $testService1,
            $testService2
        );
    }
}
