<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Service;

/**
 * Class ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests
 *
 * @covers \Jalismrs\Stalactite\Client\Service
 */
class ServiceTest extends
    AbstractTestService
{
    /**
     * testAuthentication
     *
     * @return void
     */
    public function testAuthentication() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->authentication();
        $service2 = $systemUnderTest->authentication();
        
        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
    
    /**
     * createSystemUnderTest
     *
     * @return \Jalismrs\Stalactite\Client\Service
     */
    private function createSystemUnderTest() : Service
    {
        $testClient = ClientFactory::createClient();
        
        return new Service($testClient);
    }
    
    /**
     * testData
     *
     * @return void
     */
    public function testData() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->data();
        $service2 = $systemUnderTest->data();
        
        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
}
