<?php
declare(strict_types = 1);

namespace Test\Access\Domain;

use Jalismrs\Stalactite\Client\Access\Domain\Client;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelationModel;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelationModel;
use Jalismrs\Stalactite\Client\ClientException;
use Test\Access\ModelFactory;
use Test\Data\ModelFactory as DataTestModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiGetRelationsTest
 *
 * @package Test\Access\Domain
 */
class ApiGetRelationsTest extends
    TestCase
{
    /**
     * testGetRelations
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testGetRelations() : void
    {
        $domainUserRelation = ModelFactory::getTestableDomainUserRelation()
                                          ->asArray();
        unset($domainUserRelation['domain']);
        
        $domainCustomerRelation = ModelFactory::getTestableDomainCustomerRelation()
                                              ->asArray();
        unset($domainCustomerRelation['domain']);
        
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
                                        $domainUserRelation
                                    ],
                                    'customers' => [
                                        $domainCustomerRelation
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
            DomainUserRelationModel::class,
            $response->getData()['relations']['users']
        );
        static::assertContainsOnlyInstancesOf(
            DomainCustomerRelationModel::class,
            $response->getData()['relations']['customers']
        );
    }
    
    /**
     * testThrowOnInvalidResponseGetRelations
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowOnInvalidResponseGetRelations() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $domainUserRelation = ModelFactory::getTestableDomainUserRelation()
                                          ->asArray();
        unset($domainUserRelation['domain']);
        
        $domainCustomerRelation = ModelFactory::getTestableDomainCustomerRelation()
                                              ->asArray();
        unset($domainCustomerRelation['domain']);
        
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
                                    'users'     => $domainUserRelation,
                                    'customers' => $domainCustomerRelation
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
