<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\ServerApp;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointDeleteTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\ServerApp
 *
 * @covers \Jalismrs\Stalactite\Client\Authentication\ServerApp\Service
 */
class EndpointDeleteTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowMissingUid() : void
    {
        $this->expectException(AuthenticationServiceException::class);
        $this->expectExceptionCode(AuthenticationServiceException::MISSING_SERVER_APP_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->delete(
            TestableModelFactory::getTestableServerApp()
                        ->setUid(null),
            JwtFactory::create()
        );
    }
    
    /**
     * @throws AuthenticationServiceException
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);
        
        $systemUnderTest->delete(
            TestableModelFactory::getTestableServerApp(),
            JwtFactory::create()
        );
    }
}
