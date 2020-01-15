<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AccessManagement\Customer\CustomerClient;
use Jalismrs\Stalactite\Client\AccessManagement\Model\AccessClearance;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\AccessManagement\ModelFactory;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory as DataManagementTestModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement\Customer
 */
class CustomerClientTest extends
    TestCase
{
    /**
     * testGetRelations
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testGetRelations() : void
    {
        $relation        = ModelFactory::getTestableDomainCustomerRelation();
        $relationAsArray = $relation->asArray();
        unset($relationAsArray['customer']);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'   => true,
                            'error'     => null,
                            'relations' => [$relationAsArray]
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new CustomerClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockAPIClient->getRelations(
            DataManagementTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
        
        self::assertTrue($response->success());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            DomainCustomerRelation::class,
            $response->getData()['relations']
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetRelations() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'   => true,
                            'error'     => null,
                            'relations' => ModelFactory::getTestableDomainCustomerRelation()
                                                       ->asArray()
                            // invalid
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new CustomerClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockAPIClient->getRelations(
            DataManagementTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
    }
    
    /**
     * testGetAccessClearance
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
    public function testGetAccessClearance() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'   => true,
                            'error'     => null,
                            'clearance' => ModelFactory::getTestableAccessClearance()
                                                       ->asArray()
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new CustomerClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockAPIClient->getAccessClearance(
            DataManagementTestModelFactory::getTestableCustomer(),
            DataManagementTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            AccessClearance::class,
            $response->getData()['clearance']
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetAccessClearance() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'   => true,
                            'error'     => null,
                            'clearance' => []
                            // wrong type
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new CustomerClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockAPIClient->getAccessClearance(
            DataManagementTestModelFactory::getTestableCustomer(),
            DataManagementTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
    }
}
