<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\AuthToken\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Access\AuthToken\User\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiDeleteRelationsByUserTest
 *
 * @packageJalismrs\Stalactite\Service\Tests\Access\AuthToken\User
 */
class ApiDeleteRelationsByUserTest extends
    TestCase
{
    /**
     * testDeleteRelationsByUser
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testDeleteRelationsByUser(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
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

        $response = $mockService->deleteRelationsByUser(
            ModelFactory::getTestableUser(),
            'fake API auth token'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
    }

    /**
     * testThrowExceptionOnInvalidResponseDeleteRelationByUser
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseDeleteRelationByUser(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
    
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
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

        $mockService->deleteRelationsByUser(
            ModelFactory::getTestableUser(),
            'fake API auth token'
        );
    }
}
