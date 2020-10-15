<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data;

use Jalismrs\Stalactite\Client\Tests\AbstractTestService;

/**
 * Class ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data
 */
class ServiceTest extends
    AbstractTestService
{
    use SystemUnderTestTrait;
    
    public function testCustomer() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->customers();
        $service2 = $systemUnderTest->customers();
        
        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
    
    public function testDomain() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->domains();
        $service2 = $systemUnderTest->domains();
        
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
    
    public function testPermission() : void
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
    
    public function testUser() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->users();
        $service2 = $systemUnderTest->users();
        
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
}
