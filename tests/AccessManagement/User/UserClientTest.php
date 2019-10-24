<?php

namespace jalismrs\Stalactite\Client\Test\AccessManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\AccessManagement\Model\AccessClearance;
use jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelation;
use jalismrs\Stalactite\Client\AccessManagement\User\UserClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\Test\AccessManagement\ModelFactory;
use jalismrs\Stalactite\Client\Test\DataManagement\ModelFactory as DataManagementTestModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class UserClientTest extends TestCase
{
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGetRelations(): void
    {
        $relation = ModelFactory::getTestableDomainUserRelation();
        $relationAsArray = $relation->asArray();
        unset($relationAsArray['user']);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'relations' => [$relationAsArray]
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->getRelations(DataManagementTestModelFactory::getTestableUser(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertContainsOnlyInstancesOf(DomainUserRelation::class, $response->getData()['relations']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetRelations(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'relations' => ModelFactory::getTestableDomainUserRelation()->asArray() // invalid
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->getRelations(DataManagementTestModelFactory::getTestableUser(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGetAccessClearance(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'clearance' => ModelFactory::getTestableAccessClearance()->asArray()
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->getAccessClearance(DataManagementTestModelFactory::getTestableUser(), DataManagementTestModelFactory::getTestableDomain(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertInstanceOf(AccessClearance::class, $response->getData()['clearance']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetAccessClearance(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'clearance' => [] // wrong type
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->getAccessClearance(DataManagementTestModelFactory::getTestableUser(), DataManagementTestModelFactory::getTestableDomain(), 'fake user jwt');
    }
}