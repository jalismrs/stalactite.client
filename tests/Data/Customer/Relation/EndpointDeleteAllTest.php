<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer\Relation;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

class EndpointDeleteAllTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowCustomerLacksUid() : void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_CUSTOMER_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->deleteAll(
            ModelFactory::getTestableCustomer()
                        ->setUid(null),
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
        
        $systemUnderTest->deleteAll(
            ModelFactory::getTestableCustomer(),
            JwtFactory::create()
        );
    }
}
