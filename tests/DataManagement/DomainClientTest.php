<?php

namespace jalismrs\Stalactite\Client\Test;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\DomainClient;
use jalismrs\Stalactite\Client\DataManagement\Model\Domain;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class DomainClientTest extends TestCase
{
    /**
     * @return Domain
     */
    private static function getTestableDomain(): Domain
    {
        $domain = new Domain();
        $domain->setName('azerty')
            ->setType('api')
            ->setApiKey('azertyuiopqsdfghjklmwxcvbn')
            ->setExternalAuth(false)
            ->setGenerationDate('2000-01-01')
            ->setUid('azertyuiop');

        return $domain;
    }

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
                'domains' => [self::getTestableDomain()->asArray()]
            ]))
        ]);

        $mockAPIClient = new DomainClient('http://fakeClient');
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
                'domains' => self::getTestableDomain()->asArray() // invalid type
            ]))
        ]);

        $mockAPIClient = new DomainClient('http://fakeClient');
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
                'domain' => self::getTestableDomain()->asArray()
            ]))
        ]);

        $mockAPIClient = new DomainClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->get(self::getTestableDomain(), 'fake user jwt'));
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
                'domain' => [] // invalid domain
            ]))
        ]);

        $mockAPIClient = new DomainClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->get(self::getTestableDomain(), 'fake user jwt');
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
                'domain' => self::getTestableDomain()->asArray()
            ]))
        ]);

        $mockAPIClient = new DomainClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->create(self::getTestableDomain(), 'fake user jwt'));
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
                'domain' => [] // invalid certification type
            ]))
        ]);

        $mockAPIClient = new DomainClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->create(self::getTestableDomain(), 'fake user jwt');
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

        $mockAPIClient = new DomainClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->update(self::getTestableDomain(), 'fake user jwt'));
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

        $mockAPIClient = new DomainClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->update(self::getTestableDomain(), 'fake user jwt');
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

        $mockAPIClient = new DomainClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->delete(self::getTestableDomain(), 'fake user jwt'));
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

        $mockAPIClient = new DomainClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->delete(self::getTestableDomain(), 'fake user jwt');
    }
}