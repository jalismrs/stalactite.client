<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AccessManagement\Customer\Client;
use Jalismrs\Stalactite\Client\AccessManagement\Model\AccessClearanceModel;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelationModel;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\AccessManagement\ModelFactory;
use Jalismrs\Stalactite\Test\ClientTestTrait;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory as DataManagementTestModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement\CustomerModel
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testClientMe
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientMe() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->clientMe();
        $client2 = $baseClient->clientMe();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
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
        
        $mockAPIClient = new Client(
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
            DomainCustomerRelationModel::class,
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
        
        $mockAPIClient = new Client(
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
        
        $mockAPIClient = new Client(
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
            AccessClearanceModel::class,
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
        
        $mockAPIClient = new Client(
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
