<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Permission;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Model\Permission;
use Jalismrs\Stalactite\Client\Data\Permission\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class ApiGetAllTest
 * @package Jalismrs\Stalactite\Client\Tests\Data\Post
 */
class EndpointGetAllTest extends AbstractTestEndpoint
{
    /**
     * @throws JsonException
     * @throws NormalizerException
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testGetAll(): void
    {
        $testClient = new Client('http://fakeHost');
        $testService = new Service($testClient);
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode([
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestablePermission(),
                            [
                                AbstractNormalizer::GROUPS => ['main']
                            ]
                        )
                ], JSON_THROW_ON_ERROR)
            )
        );

        $response = $testService->all(JwtFactory::create());

        self::assertContainsOnlyInstancesOf(Permission::class, $response->getBody());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockClient = $this->createMockClient();
        $testService = new Service($mockClient);
        
        $testService->all(JwtFactory::create());
    }
}
