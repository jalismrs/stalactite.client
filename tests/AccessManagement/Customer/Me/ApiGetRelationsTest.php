<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement\Customer\Me;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AccessManagement\Customer\Me\Client;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelationModel;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\AccessManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiGetRelationsTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement\Customer\Me
 */
class ApiGetRelationsTest extends
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
        $domainCustomerRelation = ModelFactory::getTestableDomainCustomerRelation()
                                              ->asArray();
        unset($domainCustomerRelation['customer']);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'   => true,
                            'error'     => null,
                            'relations' => [
                                $domainCustomerRelation
                            ]
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
            'fake user jwt'
        );
    }
}
