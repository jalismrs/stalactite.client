<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Post\Service;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * ApiDeleteTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Post
 */
class ApiDeleteTest extends
    TestCase
{
    /**
     * testDelete
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testDelete(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => null
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->deletePost(
            ModelFactory::getTestablePost()
                ->getUid(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
    }

    /**
     * testThrowExceptionOnInvalidResponseDelete
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseDelete(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => false
                        // invalid type
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $mockService->deletePost(
            ModelFactory::getTestablePost()
                ->getUid(),
            'fake user jwt'
        );
    }
}
