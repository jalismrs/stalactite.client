<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\User\Post\Client;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiAddPostsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\User\Post
 */
class ApiAddPostsTest extends
    TestCase
{
    /**
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAddPosts(): void
    {
        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $response = $mockAPIClient->addPosts(
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
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseAddPosts(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => false
                                // invalid type
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $mockAPIClient->addPosts(
            ModelFactory::getTestableUser(),
            [
                ModelFactory::getTestablePost()
            ],
            'fake user jwt'
        );
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidPostsParameterAddPosts(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $mockAPIClient->addPosts(
            ModelFactory::getTestableUser(),
            [
                'not a post'
            ],
            'fake user jwt'
        );
    }
}
