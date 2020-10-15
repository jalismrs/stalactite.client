<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Domain\Relation;

use Jalismrs\Stalactite\Client\Data\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;
use Jalismrs\Stalactite\Client\Tests\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class EndpointAddCustomerRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Domain\Relation
 */
class EndpointAddCustomerRelationTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testAddCustomerRelation() : void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                              ->normalize(
                                  ModelFactory::getTestableDomainCustomerRelation(),
                                  [
                                      AbstractNormalizer::GROUPS => ['main'],
                                  ]
                              ),
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $systemUnderTest = $this->createSystemUnderTest($testClient);
        
        $response = $systemUnderTest->addCustomerRelation(
            ModelFactory::getTestableDomain(),
            ModelFactory::getTestableCustomer(),
            JwtFactory::create()
        );
        
        static::assertInstanceOf(
            DomainCustomerRelation::class,
            $response->getBody()
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowDomainLacksUid() : void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_DOMAIN_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->addCustomerRelation(
            ModelFactory::getTestableDomain()
                        ->setUid(null),
            ModelFactory::getTestableCustomer(),
            JwtFactory::create()
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowCustomerLacksUid() : void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_CUSTOMER_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->addCustomerRelation(
            ModelFactory::getTestableDomain(),
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
        
        $systemUnderTest->addCustomerRelation(
            ModelFactory::getTestableDomain(),
            ModelFactory::getTestableCustomer(),
            JwtFactory::create()
        );
    }
}
