<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\PhoneTypeClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class PhoneTypeClientTest extends TestCase
{
    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGetAll(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'phoneTypes' => [ModelFactory::getTestablePhoneType()->asArray()]
            ]))
        ]);

        $mockAPIClient = new PhoneTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->getAll('fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetAll(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'phoneTypes' => ModelFactory::getTestablePhoneType()->asArray() // invalid type
            ]))
        ]);

        $mockAPIClient = new PhoneTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->getAll('fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGet(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'phoneType' => ModelFactory::getTestablePhoneType()->asArray()
            ]))
        ]);

        $mockAPIClient = new PhoneTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->get(ModelFactory::getTestablePhoneType(), 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGet(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'phoneType' => [] // invalid phone type
            ]))
        ]);

        $mockAPIClient = new PhoneTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->get(ModelFactory::getTestablePhoneType(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testCreate(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'phoneType' => ModelFactory::getTestablePhoneType()->asArray()
            ]))
        ]);

        $mockAPIClient = new PhoneTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->create(ModelFactory::getTestablePhoneType(), 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseCreate(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'phoneType' => [] // invalid type
            ]))
        ]);

        $mockAPIClient = new PhoneTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->create(ModelFactory::getTestablePhoneType(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testUpdate(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ]))
        ]);

        $mockAPIClient = new PhoneTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->update(ModelFactory::getTestablePhoneType(), 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseUpdate(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ]))
        ]);

        $mockAPIClient = new PhoneTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->update(ModelFactory::getTestablePhoneType(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testDelete(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ]))
        ]);

        $mockAPIClient = new PhoneTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->delete(ModelFactory::getTestablePhoneType(), 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseDelete(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ]))
        ]);

        $mockAPIClient = new PhoneTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->delete(ModelFactory::getTestablePhoneType(), 'fake user jwt'));
    }
}