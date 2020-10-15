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
    
    public function testTrustedApp() : void
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
}
