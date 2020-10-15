<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Permission;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointUpdateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Permission
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Permission\Service
 */
class EndpointUpdateTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid() : void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_PERMISSION_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->update(
            ModelFactory::getTestablePermission()
                        ->setUid(null),
            JwtFactory::create()
        );
    }
    
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);
        
        $systemUnderTest->update(
            ModelFactory::getTestablePermission(),
            JwtFactory::create()
        );
    }
}
