<?php

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User\Me;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\User\Me\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ApiGetMyPostsTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws SerializerException
     */
    public function testGetMyPosts(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode([
                    Serializer::getInstance()
                        ->normalize(
                            ModelFactory::getTestablePost(),
                            [
                                AbstractNormalizer::GROUPS => ['main']
                            ]
                        )
                ], JSON_THROW_ON_ERROR)
            )
        );

        $response = $mockService->getMyPosts('fake user jwt');

        self::assertContainsOnlyInstancesOf(Post::class, $response->getBody());
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->getMyPosts('fake user jwt');
    }
}