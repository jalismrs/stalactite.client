<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Domain;

use Jalismrs\Stalactite\Client\Access\Domain\Client;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetRelationsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access\Domain
 */
class ApiGetRelationsTest extends
    TestCase
{
    /**
     * testGetRelations
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testGetRelations() : void
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
                                'success'   => true,
                                'error'     => null,
                                'relations' => [
                                    'users'     => [
                                        $serializer->normalize(
                                            ModelFactory::getTestableDomainUserRelation(),
                                            [
                                                AbstractNormalizer::GROUPS             => [
                                                    'main',
                                                ],
                                                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                                                    'domain'
                                                ],
                                            ]
                                        )
                                    ],
                                    'customers' => [
                                        $serializer->normalize(
                                            ModelFactory::getTestableDomainCustomerRelation(),
                                            [
                                                AbstractNormalizer::GROUPS             => [
                                                    'main',
                                                ],
                                                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                                                    'domain'
                                                ],
                                            ]
                                        )
                                    ]
                                ]
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $response = $mockAPIClient->getRelations(
            DataTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
        static::assertTrue($response->isSuccess());
        static::assertNull($response->getError());
        
        static::assertIsArray($response->getData()['relations']);
        static::assertArrayHasKey(
            'users',
            $response->getData()['relations']
        );
        static::assertArrayHasKey(
            'customers',
            $response->getData()['relations']
        );
        
        static::assertContainsOnlyInstancesOf(
            DomainUserRelation::class,
            $response->getData()['relations']['users']
        );
        static::assertContainsOnlyInstancesOf(
            DomainCustomerRelation::class,
            $response->getData()['relations']['customers']
        );
    }
    
    /**
     * testThrowOnInvalidResponseGetRelations
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowOnInvalidResponseGetRelations() : void
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
                                'success'   => true,
                                'error'     => null,
                                'relations' => [
                                    'users'     => $serializer->normalize(
                                        ModelFactory::getTestableDomainUserRelation(),
                                        [
                                            AbstractNormalizer::GROUPS             => [
                                                'main',
                                            ],
                                            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                                                'domain'
                                            ],
                                        ]
                                    ),
                                    'customers' => $serializer->normalize(
                                        ModelFactory::getTestableDomainCustomerRelation(),
                                        [
                                            AbstractNormalizer::GROUPS             => [
                                                'main',
                                            ],
                                            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                                                'domain'
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
        
        $mockAPIClient->getRelations(
            DataTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
    }
}
