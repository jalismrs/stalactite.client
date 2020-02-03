<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Access\AuthToken\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Access\AuthToken\Domain\Service;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetRelationsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access\AuthToken\Domain
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
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGetRelations() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'   => true,
                                'error'     => null,
                                'relations' => [
                                    'users'     => [
                                        $mockClient
                                            ->getSerializer()
                                            ->normalize(
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
                                        $mockClient
                                            ->getSerializer()
                                            ->normalize(
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
        
        $response = $mockService->getRelations(
            DataTestModelFactory::getTestableDomain(),
            'fake auth token jwt'
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
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function testThrowOnInvalidResponseGetRelations() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'   => true,
                                'error'     => null,
                                'relations' => [
                                    'users'     => $mockClient
                                        ->getSerializer()
                                        ->normalize(
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
                                    'customers' => $mockClient
                                        ->getSerializer()
                                        ->normalize(
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
        
        $mockService->getRelations(
            DataTestModelFactory::getTestableDomain(),
            'fake auth token jwt'
        );
    }
}
