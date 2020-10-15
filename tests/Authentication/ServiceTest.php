<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Tests\AbstractTestService;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication
 */
class ServiceTest extends
    AbstractTestService
{
    use SystemUnderTestTrait;
    
    /**
     * testClientApps
     *
     * @return void
     */
    public function testClientApps() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->clientApps();
        $service2 = $systemUnderTest->clientApps();
        
        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
    
    /**
     * testServerApps
     *
     * @return void
     */
    public function testServerApps() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->serverApps();
        $service2 = $systemUnderTest->serverApps();
        
        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
    
    /**
     * testTokens
     *
     * @return void
     */
    public function testTokens() : void
    {
        $systemUnderTest = $this->createSystemUnderTest();
        
        $service1 = $systemUnderTest->tokens();
        $service2 = $systemUnderTest->tokens();
        
        self::checkServices(
            $systemUnderTest,
            $service1,
            $service2
        );
    }
}
