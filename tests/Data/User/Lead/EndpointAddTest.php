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
 * Class EndpointAddTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\User\Lead
 *
 * @covers \Jalismrs\Stalactite\Client\Data\User\Lead\Service
 */
class EndpointAddTest extends
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
        
        $systemUnderTest->add(
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
    public function testThrowOnInvalidLeadsParameterAddLeads() : void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::INVALID_MODEL);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->add(
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
        
        $systemUnderTest->add(
            ModelFactory::getTestableUser(),
            [ModelFactory::getTestablePost(),],
            JwtFactory::create()
        );
    }
}
