<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User\Post;

use InvalidArgumentException;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\User\Post\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * ApiRemovePostsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\User\Post
 */
class ApiRemovePostsTest extends
    TestCase
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
     * @throws ValidatorException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
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
     * testThrowOnInvalidResponseRemovePosts
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testThrowOnInvalidResponseRemovePosts() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => false
                        // invalid type
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $mockService->removePosts(
            ModelFactory::getTestableUser(),
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
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testThrowOnInvalidPostsParameterRemovePosts() : void
    {
        $this->expectException(InvalidArgumentException::class);
        
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
}
