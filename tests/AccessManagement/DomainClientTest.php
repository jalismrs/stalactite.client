<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AccessManagement\DomainClient;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory as DataManagementTestModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * DomainClientTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement
 */
class DomainClientTest extends
    TestCase
{
    /**
     * testGetRelations
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testGetRelations() : void
    {
        $ur = ModelFactory::getTestableDomainUserRelation()
                          ->asArray();
        unset($ur['domain']);
        
        $cr = ModelFactory::getTestableDomainCustomerRelation()
                          ->asArray();
        unset($cr['domain']);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'   => true,
                            'error'     => null,
                            'relations' => [
                                'users'     => [$ur],
                                'customers' => [$cr]
                            ]
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new DomainClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockAPIClient->getRelations(
            DataManagementTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
        static::assertTrue($response->success());
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
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseGetRelations() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $ur = ModelFactory::getTestableDomainUserRelation()
                          ->asArray();
        unset($ur['domain']);
        
        $cr = ModelFactory::getTestableDomainCustomerRelation()
                          ->asArray();
        unset($cr['domain']);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'   => true,
                            'error'     => null,
                            'relations' => [
                                'users'     => $ur,
                                'customers' => $cr
                            ]
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new DomainClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockAPIClient->getRelations(
            DataManagementTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
    }
    
    /**
     * testAddUserRelation
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testAddUserRelation() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'  => true,
                            'error'    => null,
                            'relation' => ModelFactory::getTestableDomainUserRelation()
                                                      ->asArray()
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new DomainClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockAPIClient->addUserRelation(
            DataManagementTestModelFactory::getTestableDomain(),
            DataManagementTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
        static::assertTrue($response->success());
        static::assertNull($response->getError());
        static::assertInstanceOf(DomainUserRelation::class, $response->getData()['relation']);
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseAddUserRelation() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'  => true,
                            'error'    => null,
                            'relation' => []
                            // wrong type
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new DomainClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockAPIClient->addUserRelation(
            DataManagementTestModelFactory::getTestableDomain(),
            DataManagementTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
    
    /**
     * testAddCustomerRelation
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testAddCustomerRelation() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'  => true,
                            'error'    => null,
                            'relation' => ModelFactory::getTestableDomainCustomerRelation()
                                                      ->asArray()
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new DomainClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockAPIClient->addCustomerRelation(
            DataManagementTestModelFactory::getTestableDomain(),
            DataManagementTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
        static::assertTrue($response->success());
        static::assertNull($response->getError());
        static::assertInstanceOf(DomainCustomerRelation::class, $response->getData()['relation']);
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseAddCustomerRelation() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'  => true,
                            'error'    => null,
                            'relation' => []
                            // wrong type
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new DomainClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockAPIClient->addCustomerRelation(
            DataManagementTestModelFactory::getTestableDomain(),
            DataManagementTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
    }
}
