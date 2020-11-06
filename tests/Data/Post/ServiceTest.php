<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Post;

use Jalismrs\Stalactite\Client\Tests\AbstractTestService;

/**
 * Class ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Post
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Post\Service
 */
class ServiceTest extends
    AbstractTestService
{
    use SystemUnderTestTrait;

    public function testPermission(): void
    {
        $systemUnderTest = $this->createSystemUnderTest();

        $service1 = $systemUnderTest->permissions();
        $service2 = $systemUnderTest->permissions();

        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
}
