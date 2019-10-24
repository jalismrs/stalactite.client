<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\Post;
use jalismrs\Stalactite\Client\DataManagement\User\PostClient;
use jalismrs\Stalactite\Client\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class PostClientTest extends TestCase
{
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGetAll(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'posts' => [ModelFactory::getTestablePost()->asArray()]
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new PostClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->getAll(ModelFactory::getTestableUser(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertContainsOnlyInstancesOf(Post::class, $response->getData()['posts']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseGetAll(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'posts' => ModelFactory::getTestablePost()->asArray() // invalid type
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new PostClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->getAll(ModelFactory::getTestableUser(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testAddPosts(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new PostClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->addPosts(ModelFactory::getTestableUser(), [ModelFactory::getTestablePost()], 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseAddPosts(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new PostClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->addPosts(ModelFactory::getTestableUser(), [ModelFactory::getTestablePost()], 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidPostsParameterAddPosts(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new PostClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->addPosts(ModelFactory::getTestableUser(), [ModelFactory::getTestablePost()->asArray()], 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testRemovePosts(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new PostClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->removePosts(ModelFactory::getTestableUser(), [ModelFactory::getTestablePost()], 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseRemovePosts(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new PostClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->removePosts(ModelFactory::getTestableUser(), [ModelFactory::getTestablePost()], 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidPostsParameterRemovePosts(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new PostClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->removePosts(ModelFactory::getTestableUser(), [ModelFactory::getTestablePost()->asArray()], 'fake user jwt');
    }
}