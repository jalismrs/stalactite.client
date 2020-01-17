<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement\Domain;

use Jalismrs\Stalactite\Client\AccessManagement\Domain\Client;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelationModel;
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
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testAddCustomerRelation() : void
    {
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient(
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
            )
        );
        
        $response = $mockAPIClient->addCustomerRelation(
            DataManagementTestModelFactory::getTestableDomain(),
            DataManagementTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
        static::assertTrue($response->isSuccess());
        static::assertNull($response->getError());
        static::assertInstanceOf(DomainCustomerRelationModel::class, $response->getData()['relation']);
    }
    
    /**
     * testThrowExceptionOnInvalidResponseAddCustomerRelation
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseAddCustomerRelation() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient(
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
            )
        );
        
        $mockAPIClient->addCustomerRelation(
            DataManagementTestModelFactory::getTestableDomain(),
            DataManagementTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
    }
}
