<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\User\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetRelationsTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Access\User
 */
class ApiGetRelationsTest extends
    TestCase
{
    /**
     * testGetRelations
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
    public function testGetRelations(): void
    {
        $serializer = Serializer::getInstance();

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
                                'relations' => [
                                    $serializer->normalize(
                                        ModelFactory::getTestableDomainUserRelation(),
                                        [
                                            AbstractNormalizer::GROUPS => [
                                                'main',
                                            ],
                                            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                                                'user'
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

        $response = $mockAPIClient->getRelations(
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            DomainUserRelation::class,
            $response->getData()['relations']
        );
    }

    /**
     * testThrowExceptionOnInvalidResponseGetRelations
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function testThrowExceptionOnInvalidResponseGetRelations(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $serializer = Serializer::getInstance();

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
                                'relations' => $serializer->normalize(
                                    ModelFactory::getTestableDomainUserRelation(),
                                    [
                                        AbstractNormalizer::GROUPS => [
                                            'main',
                                        ],
                                    ]
                                )
                                // invalid
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $mockAPIClient->getRelations(
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
}
