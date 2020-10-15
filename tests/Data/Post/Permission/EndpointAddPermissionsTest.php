<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Post\Permission;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Util\Response;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointAddPermissionsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Post\Permission
 */
class EndpointAddPermissionsTest extends
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
        $this->expectExceptionCode(DataServiceException::MISSING_POST_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->addPermissions(
            ModelFactory::getTestablePost()
                        ->setUid(null),
            [],
            JwtFactory::create()
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowOnInvalidPermissionList() : void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::INVALID_MODEL);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->addPermissions(
            ModelFactory::getTestablePost(),
            ['not a permission'],
            JwtFactory::create()
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodNotCalledOnEmptyPermissionList() : void
    {
        $mockClient = $this->createMockClient(false);
        
        $systemUnderTest = $this->createSystemUnderTest($mockClient);
        
        $response = $systemUnderTest->addPermissions(
            ModelFactory::getTestablePost(),
            [],
            JwtFactory::create()
        );
        
        self::assertNull($response);
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);
        
        $response = $systemUnderTest->addPermissions(
            ModelFactory::getTestablePost(),
            [ModelFactory::getTestablePermission()],
            JwtFactory::create()
        );
        self::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
