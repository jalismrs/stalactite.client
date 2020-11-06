<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer\Me;

use Jalismrs\Stalactite\Client\Tests\AbstractTestService;

/**
 * Class ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Customer\Me
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Customer\Me\Service
 */
class ServiceTest extends
    AbstractTestService
{
    use SystemUnderTestTrait;

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
