<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\ServerApp;

use Jalismrs\Stalactite\Client\Authentication\Model\ServerApp;
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
 * Class ApiCreateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\ServerApp
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
                                  ModelFactory::getTestableServerApp(),
                                  [AbstractNormalizer::GROUPS => ['main']]
                              ),
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $systemUnderTest = $this->createSystemUnderTest($testClient);
        
        $response = $systemUnderTest->create(
            ModelFactory::getTestableServerApp()
                        ->setUid(null),
            JwtFactory::create()
        );
        
        self::assertInstanceOf(
            ServerApp::class,
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
            ModelFactory::getTestableServerApp()
                        ->setUid(null),
            JwtFactory::create()
        );
    }
}
