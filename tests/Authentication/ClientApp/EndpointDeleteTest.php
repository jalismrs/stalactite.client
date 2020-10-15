<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\ClientApp;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * ApiDeleteTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\TrustedApp
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
        $this->expectExceptionCode(AuthenticationServiceException::MISSING_CLIENT_APP_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->delete(
            ModelFactory::getTestableClientApp()
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
            ModelFactory::getTestableClientApp(),
            JwtFactory::create()
        );
    }
}
