<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data;

use Jalismrs\Stalactite\Client\Data\Service;
use Jalismrs\Stalactite\Client\Tests\AbstractTestService;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;

class ServiceTest extends AbstractTestService
{
    public function testCustomer(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->customers();
        $testService2 = $testService->customers();

        self::checkServices(
            $testService,
            $testService1,
            $testService2
        );
    }

    public function testDomain(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->domains();
        $testService2 = $testService->domains();

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

    public function testPermission(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->permissions();
        $testService2 = $testService->permissions();

        self::checkServices(
            $testService,
            $testService1,
            $testService2
        );
    }

    public function testUser(): void
    {
        $testClient = ClientFactory::createClient();
        $testService = new Service($testClient);
        $testService1 = $testService->users();
        $testService2 = $testService->users();

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
