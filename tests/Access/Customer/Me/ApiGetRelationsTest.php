<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Customer\Me;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Access\Customer\Me\Client;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetRelationsTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Access\Customer\Me
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
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
                                        ModelFactory::getTestableDomainCustomerRelation(),
                                        [
                                            AbstractNormalizer::GROUPS => [
                                                'main',
                                            ],
                                            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                                                'customer'
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
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            DomainCustomerRelation::class,
            $response->getData()['relations']
        );
    }

    /**
     * testThrowExceptionOnInvalidResponseGetRelations
     *
     * @return void
     *
     * @throws ClientException
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
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
                                    ModelFactory::getTestableDomainCustomerRelation(),
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
            'fake user jwt'
        );
    }
}
