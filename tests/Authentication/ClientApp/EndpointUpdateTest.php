<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\ClientApp;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointUpdateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\ClientApp
 *
 * @covers \Jalismrs\Stalactite\Client\Authentication\ClientApp\Service
 */
class EndpointUpdateTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws AuthenticationServiceException
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid() : void
    {
        $this->expectException(AuthenticationServiceException::class);
        $this->expectExceptionCode(AuthenticationServiceException::MISSING_CLIENT_APP_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->update(
            TestableModelFactory::getTestableClientApp()
                        ->setUid(null),
            JwtFactory::create()
        );
    }
    
    /**
     * @throws AuthenticationServiceException
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);
        
        $systemUnderTest->update(
            TestableModelFactory::getTestableClientApp(),
            JwtFactory::create()
        );
    }
}
