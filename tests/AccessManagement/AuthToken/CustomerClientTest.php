<?php

namespace jalismrs\Stalactite\Client\Test\AccessManagement\AuthToken;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\AccessManagement\AuthToken\CustomerClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class CustomerClientTest extends TestCase
{
    /**
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function testDeleteRelationByCustomer(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CustomerClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->deleteRelationsByCustomer(ModelFactory::getTestableCustomer(), 'fake API auth token');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseDeleteRelationByDomain(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // wrong type
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CustomerClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->deleteRelationsByCustomer(ModelFactory::getTestableCustomer(), 'fake API auth token');
    }
}