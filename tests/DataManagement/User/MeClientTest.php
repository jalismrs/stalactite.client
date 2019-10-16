<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\User\MeClient;
use jalismrs\Stalactite\Client\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class MeClientTest extends TestCase
{
    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGet(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'me' => ModelFactory::getTestableUser()->asArray()
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->get('fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertInstanceOf(User::class, $response->getData()['me']);
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

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'me' => [ModelFactory::getTestableUser()->asArray()] // invalid type
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->get('fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testUpdate(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->update(ModelFactory::getTestableUser(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
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

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->update(ModelFactory::getTestableUser(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
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

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->addPhoneLine(ModelFactory::getTestablePhoneLine(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
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

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->addPhoneLine(ModelFactory::getTestablePhoneLine(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidPhoneTypeAddPhoneLine(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT);

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null // invalid type
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $phoneLine = ModelFactory::getTestablePhoneLine()->setType(null);

        $mockAPIClient->addPhoneLine($phoneLine, 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
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

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->removePhoneLine(ModelFactory::getTestablePhoneLine(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseRemovePhoneLine(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ], JSON_THROW_ON_ERROR))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->removePhoneLine(ModelFactory::getTestablePhoneLine(), 'fake user jwt');
    }
}