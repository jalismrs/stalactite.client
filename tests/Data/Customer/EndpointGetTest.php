<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer;

use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
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
 * ApiGetTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Customer
 */
class EndpointGetTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testGet() : void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                              ->normalize(
                                  ModelFactory::getTestableCustomer(),
                                  [
                                      AbstractNormalizer::GROUPS => ['main'],
                                  ]
                              ),
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $systemUnderTest = $this->createSystemUnderTest($testClient);
        
        $response = $systemUnderTest->get(
            ModelFactory::getTestableCustomer()
                        ->getUid(),
            JwtFactory::create()
        );
        
        self::assertInstanceOf(
            Customer::class,
            $response->getBody()
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
        
        $systemUnderTest->get(
            ModelFactory::getTestableCustomer()
                        ->getUid(),
            JwtFactory::create()
        );
    }
}
