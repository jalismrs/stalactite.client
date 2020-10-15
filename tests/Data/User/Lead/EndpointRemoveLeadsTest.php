<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User\Lead;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * ApiRemoveLeadsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\User\Lead
 */
class EndpointRemoveLeadsTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid() : void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_USER_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->remove(
            ModelFactory::getTestableUser()
                        ->setUid(null),
            [ModelFactory::getTestablePost()],
            JwtFactory::create()
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowOnInvalidPostsParameterRemoveLeads() : void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::INVALID_MODEL);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->remove(
            ModelFactory::getTestableUser(),
            ['not a lead'],
            JwtFactory::create()
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);
        
        $systemUnderTest->remove(
            ModelFactory::getTestableUser(),
            [ModelFactory::getTestablePost()],
            JwtFactory::create()
        );
    }
}
