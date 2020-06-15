<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\Post;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Post\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiCreateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\Post
 */
class ApiCreateTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     */
    public function testCreate(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestablePost(),
                            [
                                AbstractNormalizer::GROUPS => ['main']
                            ]
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->createPost(ModelFactory::getTestablePost(), JwtFactory::create());

        self::assertInstanceOf(Post::class, $response->getBody());
    }

    /**
     * @throws ClientException
     * @throws NormalizerException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->createPost(ModelFactory::getTestablePost()->setUid(null), JwtFactory::create());
    }
}
