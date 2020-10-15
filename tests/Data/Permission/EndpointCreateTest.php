<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Permission;

use Jalismrs\Stalactite\Client\Data\Model\Permission;
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
 * ApiCreateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Post
 */
class EndpointCreateTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws JsonException
     * @throws ClientException
     * @throws NormalizerException
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
                                  ModelFactory::getTestablePermission(),
                                  [
                                      AbstractNormalizer::GROUPS => ['main'],
                                  ]
                              ),
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $systemUnderTest = $this->createSystemUnderTest($testClient);
        
        $response = $systemUnderTest->create(
            ModelFactory::getTestablePermission(),
            JwtFactory::create()
        );
        
        self::assertInstanceOf(
            Permission::class,
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
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);
        
        $systemUnderTest->create(
            ModelFactory::getTestablePermission()
                        ->setUid(null),
            JwtFactory::create()
        );
    }
}
