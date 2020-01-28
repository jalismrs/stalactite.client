<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Post\Client;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetUsersTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Post
 */
class ApiGetUsersTest extends
    TestCase
{
    /**
     * testGetUsers
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGetUsers(): void
    {
        $serializer = Serializer::getInstance();

        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'users' => [
                                    $serializer->normalize(
                                        ModelFactory::getTestableUser(),
                                        [
                                            AbstractNormalizer::GROUPS => [
                                                'main',
                                            ],
                                        ]
                                    )
                                ]
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $response = $mockAPIClient->getUsers(
            ModelFactory::getTestablePost()
                ->getUid(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            User::class,
            $response->getData()['users']
        );
    }

    /**
     * testThrowExceptionOnInvalidResponseGetUsers
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetUsers(): void
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
                                'error' => null,
                                'users' => null
                                // invalid type
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $mockAPIClient->getUsers(
            ModelFactory::getTestablePost()
                ->getUid(),
            'fake user jwt'
        );
    }
}
