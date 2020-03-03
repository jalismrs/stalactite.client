<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User\Post;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\User\Post\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;

/**
 * ApiAddPostsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\User\Post
 */
class ApiAddPostsTest extends EndpointTest
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

        $mockService->addPosts(
            ModelFactory::getTestableUser()->setUid(null),
            [ModelFactory::getTestablePost()],
            'fake user jwt'
        );
    }

    /**
     * @throws ClientException
     */
    public function testThrowOnInvalidPostsParameterAddPosts(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::INVALID_MODEL);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->addPosts(
            ModelFactory::getTestableUser(),
            ['not a post'],
            'fake user jwt'
        );
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->addPosts(ModelFactory::getTestableUser(), [ModelFactory::getTestablePost()], 'fake user jwt');
    }
}
