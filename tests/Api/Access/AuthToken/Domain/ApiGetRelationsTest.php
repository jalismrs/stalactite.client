<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\AuthToken\Domain;

use Jalismrs\Stalactite\Client\Access\AuthToken\Domain\Service;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Api\ApiAbstract;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetRelationsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\AuthToken\Domain
 */
class ApiGetRelationsTest extends
    ApiAbstract
{
    /**
     * testGetRelations
     *
     * @return void
     *
     * @throws ClientException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testGetRelations() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success'   => true,
                        'error'     => null,
                        'relations' => [
                            'users'     => [
                                Serializer::getInstance()
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
                                Serializer::getInstance()
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testThrowOnInvalidResponseGetRelations() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success'   => true,
                        'error'     => null,
                        'relations' => [
                            'users'     => Serializer::getInstance()
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
                            'customers' => Serializer::getInstance()
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
        );
        
        $mockService->getRelations(
            DataTestModelFactory::getTestableDomain(),
            'fake auth token jwt'
        );
    }
    
    /**
     * testRequestMethodCalledOnce
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws RuntimeException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockService = new Service($this->createMockClient());
        
        $mockService->getRelations(
            DataTestModelFactory::getTestableDomain(),
            'fake auth token jwt'
        );
    }
}
