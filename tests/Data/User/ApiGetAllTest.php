<?php
declare(strict_types=1);

namespace Test\Data\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\UserModel;
use Jalismrs\Stalactite\Client\Data\User\Client;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Test\Data\ModelFactory;

/**
 * ApiGetAllTest
 *
 * @package Test\Data\User
 */
class ApiGetAllTest extends
    TestCase
{
    /**
     * testGetAll
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGetAll(): void
    {
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'users' => [
                                    ModelFactory::getTestableUser()
                                        ->asMinimalArray()
                                ]
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $response = $mockAPIClient->getAllUsers(
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            UserModel::class,
            $response->getData()['users']
        );
    }

    /**
     * testThrowOnInvalidResponseOnGetAll
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseOnGetAll(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
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

        $mockAPIClient->getAllUsers(
            'fake user jwt'
        );
    }
}
