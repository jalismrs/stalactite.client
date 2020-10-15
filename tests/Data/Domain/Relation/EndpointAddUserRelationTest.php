<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Domain\Relation;

use Jalismrs\Stalactite\Client\Data\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;
use Jalismrs\Stalactite\Client\Tests\Data\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class EndpointAddUserRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Domain\Relation
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Domain\Relation\Service
 */
class EndpointAddUserRelationTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testAddUserRelation() : void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                              ->normalize(
                                  TestableModelFactory::getTestableDomainUserRelation(),
                                  [
                                      AbstractNormalizer::GROUPS => ['main'],
                                  ]
                              ),
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $systemUnderTest = $this->createSystemUnderTest($testClient);
        
        $response = $systemUnderTest->addUserRelation(
            TestableModelFactory::getTestableDomain(),
            TestableModelFactory::getTestableUser(),
            JwtFactory::create()
        );
        
        static::assertInstanceOf(
            DomainUserRelation::class,
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
        
        $systemUnderTest->addUserRelation(
            TestableModelFactory::getTestableDomain()
                                ->setUid(null),
            TestableModelFactory::getTestableUser(),
            JwtFactory::create()
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowUserLacksUid() : void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_USER_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->addUserRelation(
            TestableModelFactory::getTestableDomain(),
            TestableModelFactory::getTestableUser()
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
        
        $systemUnderTest->addUserRelation(
            TestableModelFactory::getTestableDomain(),
            TestableModelFactory::getTestableUser(),
            JwtFactory::create()
        );
    }
}
