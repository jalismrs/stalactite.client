<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\User\Me;

use Jalismrs\Stalactite\Client\Tests\AbstractTestService;

/**
 * Class ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\User\Me
 */
class ServiceTest extends
    AbstractTestService
{
    use SystemUnderTestTrait;
    
    public function testLead() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->leads();
        $service2 = $systemUnderTest->leads();
        
        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
    
    public function testPost() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->posts();
        $service2 = $systemUnderTest->posts();
        
        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
    
    public function testAccess() : void
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
    
    public function testRelation() : void
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
    
    public function testSubordinate() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->subordinates();
        $service2 = $systemUnderTest->subordinates();
        
        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
}
