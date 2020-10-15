<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\ServerApp;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class ApiUpdateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\ServerApp
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
        $this->expectExceptionCode(AuthenticationServiceException::MISSING_SERVER_APP_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->update(
            ModelFactory::getTestableServerApp()
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
            ModelFactory::getTestableServerApp(),
            JwtFactory::create()
        );
    }
}
