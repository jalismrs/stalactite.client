<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement\User\Me;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AccessManagement\Model\AccessClearanceModel;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelationModel;
use Jalismrs\Stalactite\Client\AccessManagement\User\Me\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\AccessManagement\ModelFactory;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory as DataManagementTestModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/***
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement\UserModel
 */
class ClientTest extends
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
        $relation        = ModelFactory::getTestableDomainUserRelation();
        $relationAsArray = $relation->asArray();
        unset($relationAsArray['user']);
        
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient(
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
            )
        );
        
        $response = $mockAPIClient->getRelations(
            'fake user jwt'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            DomainUserRelationModel::class,
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
        
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'   => true,
                                'error'     => null,
                                'relations' => ModelFactory::getTestableDomainUserRelation()
                                                           ->asArray()
                                // invalid
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $mockAPIClient->getRelations(
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
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient(
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
            )
        );
        
        $response = $mockAPIClient->getAccessClearance(
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
        
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient(
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
            )
        );
        
        $mockAPIClient->getAccessClearance(
            DataManagementTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
    }
}
