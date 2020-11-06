<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Relation;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointDeleteDomainCustomerRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Relation
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Relation\Service
 */
class EndpointDeleteDomainCustomerRelationTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_DOMAIN_CUSTOMER_RELATION_UID);

        $systemUnderTest = $this->createSystemUnderTest();

        $systemUnderTest->deleteDomainCustomerRelation(
            TestableModelFactory::getTestableDomainCustomerRelation()
                ->setUid(null),
            JwtFactory::create()
        );
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);

        $systemUnderTest->deleteDomainCustomerRelation(
            TestableModelFactory::getTestableDomainCustomerRelation(),
            JwtFactory::create()
        );
    }
}
