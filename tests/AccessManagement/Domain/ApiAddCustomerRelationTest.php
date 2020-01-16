<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AccessManagement\Domain\Client;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelationModel;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelationModel;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\AccessManagement\ModelFactory;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory as DataManagementTestModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiAddCustomerRelationTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement\Domain
 */
class ApiAddCustomerRelationTest extends
    TestCase
{
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
        
        $mockAPIClient = new Client(
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
        static::assertInstanceOf(DomainCustomerRelationModel::class, $response->getData()['relation']);
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
        
        $mockAPIClient = new Client(
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
