<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\CertificationTypeClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class CertificationTypeClientTest extends TestCase
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
                'certificationTypes' => [ModelFactory::getTestableCertificationType()->asArray()]
            ]))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
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
                'certificationTypes' => ModelFactory::getTestableCertificationType()->asArray() // invalid type
            ]))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
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
                'certificationType' => ModelFactory::getTestableCertificationType()->asArray()
            ]))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->get(ModelFactory::getTestableCertificationType(), 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseGet(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'certificationType' => [] // invalid certification type
            ]))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->get(ModelFactory::getTestableCertificationType(), 'fake user jwt');
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
                'certificationType' => ModelFactory::getTestableCertificationType()->asArray()
            ]))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->create(ModelFactory::getTestableCertificationType(), 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseCreate(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'certificationType' => [] // invalid certification type
            ]))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->create(ModelFactory::getTestableCertificationType(), 'fake user jwt');
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

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->update(ModelFactory::getTestableCertificationType(), 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseUpdate(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ]))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->update(ModelFactory::getTestableCertificationType(), 'fake user jwt');
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

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->delete(ModelFactory::getTestableCertificationType(), 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseDelete(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false /// invalid type
            ]))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->delete(ModelFactory::getTestableCertificationType(), 'fake user jwt');
    }
}