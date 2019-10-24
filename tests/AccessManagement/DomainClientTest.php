<?php

namespace jalismrs\Stalactite\Client\Test\AccessManagement;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\AccessManagement\DomainClient;
use jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelation;
use jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelation;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\Test\DataManagement\ModelFactory as DataManagementTestModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class DomainClientTest extends TestCase
{
    /**
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function testGetRelations(): void
    {
        $ur = ModelFactory::getTestableDomainUserRelation()->asArray();
        unset($ur['domain']);

        $cr = ModelFactory::getTestableDomainCustomerRelation()->asArray();
        unset($cr['domain']);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'relations' => [
                    'users' => [$ur],
                    'customers' => [$cr]
                ]
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new DomainClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->getRelations(DataManagementTestModelFactory::getTestableDomain(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());

        $this->assertIsArray($response->getData()['relations']);
        $this->assertArrayHasKey('users', $response->getData()['relations']);
        $this->assertArrayHasKey('customers', $response->getData()['relations']);

        $this->assertContainsOnlyInstancesOf(DomainUserRelation::class, $response->getData()['relations']['users']);
        $this->assertContainsOnlyInstancesOf(DomainCustomerRelation::class, $response->getData()['relations']['customers']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseGetRelations(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $ur = ModelFactory::getTestableDomainUserRelation()->asArray();
        unset($ur['domain']);

        $cr = ModelFactory::getTestableDomainCustomerRelation()->asArray();
        unset($cr['domain']);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'relations' => [
                    'users' => $ur,
                    'customers' => $cr
                ]
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new DomainClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->getRelations(DataManagementTestModelFactory::getTestableDomain(), 'fake user jwt');
    }
}