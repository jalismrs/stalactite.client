<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\Test\DataManagement;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\CertificationTypeClient;
use jalismrs\Stalactite\Client\DataManagement\Model\CertificationType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class CertificationTypeClientTest extends TestCase
{
    /**
     * @throws ClientException
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
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->getAll('fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertContainsOnlyInstancesOf(CertificationType::class, $response->getData()['certificationTypes']);
    }

    /**
     * @throws ClientException
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
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->getAll('fake user jwt');
    }

    /**
     * @throws ClientException
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
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->get(ModelFactory::getTestableCertificationType()->getUid(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertInstanceOf(CertificationType::class, $response->getData()['certificationType']);
    }

    /**
     * @throws ClientException
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
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->get(ModelFactory::getTestableCertificationType()->getUid(), 'fake user jwt');
    }

    /**
     * @throws ClientException
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
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->create(ModelFactory::getTestableCertificationType(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertInstanceOf(CertificationType::class, $response->getData()['certificationType']);
    }

    /**
     * @throws ClientException
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
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->create(ModelFactory::getTestableCertificationType(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testUpdate(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->update(ModelFactory::getTestableCertificationType(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
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
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->update(ModelFactory::getTestableCertificationType(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testDelete(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->delete(ModelFactory::getTestableCertificationType()->getUid(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
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
            ], JSON_THROW_ON_ERROR))
        ]);

        $mockAPIClient = new CertificationTypeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->delete(ModelFactory::getTestableCertificationType()->getUid(), 'fake user jwt');
    }
}
