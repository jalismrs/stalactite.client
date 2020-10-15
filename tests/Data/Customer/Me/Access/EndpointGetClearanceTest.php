<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer\Me\Access;

use Jalismrs\Stalactite\Client\Data\Model\AccessClearance;
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

class EndpointGetClearanceTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws NormalizerException
     */
    public function testGetClearance() : void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                              ->normalize(
                                  ModelFactory::getTestableAccessClearance(),
                                  [
                                      AbstractNormalizer::GROUPS => ['main'],
                                  ]
                              ),
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $systemUnderTest = $this->createSystemUnderTest($testClient);
        
        $response = $systemUnderTest->clearance(
            ModelFactory::getTestableDomain(),
            JwtFactory::create()
        );
        
        self::assertInstanceOf(
            AccessClearance::class,
            $response->getBody()
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowOnMissingDomainUid() : void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_DOMAIN_UID);
        
        $systemUnderTest = $this->createSystemUnderTest();
        
        $systemUnderTest->clearance(
            ModelFactory::getTestableDomain()
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
        
        $systemUnderTest->clearance(
            ModelFactory::getTestableDomain(),
            JwtFactory::create()
        );
    }
}
