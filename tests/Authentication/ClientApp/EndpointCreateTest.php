<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\ClientApp;

use Jalismrs\Stalactite\Client\Authentication\Model\ClientApp;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class EndpointCreateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\ClientApp
 */
class EndpointCreateTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws NormalizerException
     * @throws ClientException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testCreate() : void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                              ->normalize(
                                  ModelFactory::getTestableClientApp(),
                                  [AbstractNormalizer::GROUPS => ['main']]
                              ),
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $systemUnderTest = $this->createSystemUnderTest($testClient);
        
        $response = $systemUnderTest->create(
            ModelFactory::getTestableClientApp()
                        ->setUid(null),
            JwtFactory::create()
        );
        
        self::assertInstanceOf(
            ClientApp::class,
            $response->getBody()
        );
    }
    
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockClient      = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);
        
        $systemUnderTest->create(
            ModelFactory::getTestableClientApp(),
            JwtFactory::create()
        );
    }
}
