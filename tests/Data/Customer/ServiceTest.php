<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer;

use Jalismrs\Stalactite\Client\Tests\AbstractTestService;

/**
 * Class ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Customer
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Customer\Service
 */
class ServiceTest extends
    AbstractTestService
{
    use SystemUnderTestTrait;

    public function testMe(): void
    {
        $systemUnderTest = $this->createSystemUnderTest();

        $service1 = $systemUnderTest->me();
        $service2 = $systemUnderTest->me();

        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }

    public function testAccess(): void
    {
        $systemUnderTest = $this->createSystemUnderTest();

        $service1 = $systemUnderTest->access();
        $service2 = $systemUnderTest->access();

        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }

    public function testRelation(): void
    {
        $systemUnderTest = $this->createSystemUnderTest();

        $service1 = $systemUnderTest->relations();
        $service2 = $systemUnderTest->relations();

        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
}
