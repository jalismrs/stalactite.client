<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\User\Client;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiCreateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\User
 */
class ApiCreateTest extends
    TestCase
{
    /**
     * testCreate
     *
     * @return void
     *
     * @throws ClientException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws InvalidArgumentException
     * @throws SerializerException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCreate(): void
    {
        $serializer = Serializer::getInstance();

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'user' => $serializer->normalize(
                                    ModelFactory::getTestableUser(),
                                    [
                                        AbstractNormalizer::GROUPS => [
                                            'main',
                                        ],
                                    ]
                                )
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $response = $mockClient->createUser(
            new User(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            User::class,
            $response->getData()['user']
        );
    }

    /**
     * testThrowOnInvalidResponseOnCreate
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testThrowOnInvalidResponseOnCreate(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'user' => []
                                // invalid type
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $mockClient->createUser(
            new User(),
            'fake user jwt'
        );
    }
}
