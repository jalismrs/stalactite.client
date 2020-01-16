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
 * ApiAddUserRelationTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement\Domain
 */
class ApiAddUserRelationTest extends
    TestCase
{
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
        
        $mockAPIClient = new Client(
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
        static::assertInstanceOf(DomainUserRelationModel::class, $response->getData()['relation']);
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
        
        $mockAPIClient = new Client(
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
}
