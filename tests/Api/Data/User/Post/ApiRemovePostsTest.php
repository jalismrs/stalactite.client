<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User\Post;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\User\Post\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Api\ApiAbstract;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * ApiRemovePostsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\User\Post
 */
class ApiRemovePostsTest extends
    ApiAbstract
{
    /**
     * testRemovePosts
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testRemovePosts() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => null
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $response = $mockService->removePosts(
            ModelFactory::getTestableUser(),
            [
                ModelFactory::getTestablePost()
            ],
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
    }
    
    /**
     * testThrowLacksUid
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testThrowLacksUid() : void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('User lacks a uid');
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        $mockService->removePosts(
            ModelFactory::getTestableUser()->setUid(null),
            [
                ModelFactory::getTestablePost()
            ],
            'fake user jwt'
        );
    }
    
    /**
     * testThrowOnInvalidPostsParameterRemovePosts
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testThrowOnInvalidPostsParameterRemovePosts() : void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('Error while getting uids');
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => null
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $mockService->removePosts(
            ModelFactory::getTestableUser(),
            [
                'not a post'
            ],
            'fake user jwt'
        );
    }
    
    /**
     * testRequestMethodCalledOnce
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws RuntimeException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockService = new Service($this->createMockClient());
        
        $mockService->removePosts(
            ModelFactory::getTestableUser(),
            [
                ModelFactory::getTestablePost()
            ],
            'fake user jwt'
        );
    }
}
