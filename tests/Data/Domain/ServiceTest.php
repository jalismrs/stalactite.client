<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Domain;

use Jalismrs\Stalactite\Client\Tests\AbstractTestService;

/**
 * Class ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Domain
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Domain\Service
 */
class ServiceTest extends
    AbstractTestService
{
    use SystemUnderTestTrait;
    
    public function testLead() : void
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
