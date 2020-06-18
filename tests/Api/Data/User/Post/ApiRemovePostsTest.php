<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User\Post;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\User\Post\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;

/**
 * Class ApiRemovePostsTest
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\User\Post
 */
class ApiRemovePostsTest extends EndpointTest
{
    /**
     * @throws ClientException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_USER_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->removePosts(
            ModelFactory::getTestableUser()->setUid(null),
            [ModelFactory::getTestablePost()],
            JwtFactory::create()
        );
    }

    /**
     * @throws ClientException
     */
    public function testThrowOnInvalidPostsParameterRemovePosts(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::INVALID_MODEL);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->removePosts(ModelFactory::getTestableUser(), ['not a post'], JwtFactory::create());
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodNotCalledOnEmptyPostList(): void
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects(static::never())
            ->method('request');

        $mockService = new Service($mockClient);

        $response = $mockService->addPosts(ModelFactory::getTestableUser(), [], JwtFactory::create());
        self::assertNull($response);
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->removePosts(ModelFactory::getTestableUser(), [ModelFactory::getTestablePost()], JwtFactory::create());
    }
}
