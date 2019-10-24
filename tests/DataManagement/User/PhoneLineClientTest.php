<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneLine;
use jalismrs\Stalactite\Client\DataManagement\User\PhoneLineClient;
use jalismrs\Stalactite\Client\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class PhoneLineClientTest extends TestCase
{
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGetAll(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'phoneLines' => [ModelFactory::getTestablePhoneLine()->asArray()]
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new PhoneLineClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->getAll(ModelFactory::getTestableUser(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertContainsOnlyInstancesOf(PhoneLine::class, $response->getData()['phoneLines']);
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

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'phoneLines' => ModelFactory::getTestablePhoneLine()->asArray() // invalid type
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new PhoneLineClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->getAll(ModelFactory::getTestableUser(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testAddPhoneLine(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new PhoneLineClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->addPhoneLine(ModelFactory::getTestableUser(), ModelFactory::getTestablePhoneLine(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseAddPhoneLine(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new PhoneLineClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->addPhoneLine(ModelFactory::getTestableUser(), ModelFactory::getTestablePhoneLine(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidPhoneTypeAddPhoneLine(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT);

        $mockAPIClient = new PhoneLineClient('http://fakeHost');
        $phoneLine = ModelFactory::getTestablePhoneLine()->setType(null);

        $mockAPIClient->addPhoneLine(ModelFactory::getTestableUser(), $phoneLine, 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testRemovePhoneLine(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new PhoneLineClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->removePhoneLine(ModelFactory::getTestableUser(), ModelFactory::getTestablePhoneLine(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseRemoveCertification(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new PhoneLineClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->removePhoneLine(ModelFactory::getTestableUser(), ModelFactory::getTestablePhoneLine(), 'fake user jwt');
    }
}