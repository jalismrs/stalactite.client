<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\User\UserClient;
use jalismrs\Stalactite\Client\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class UserClientTest extends TestCase
{
    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGetAll(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'users' => [
                    ModelFactory::getTestableUser()->asMinimalArray()
                ]
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->getAll('fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertContainsOnlyInstancesOf(User::class, $response->getData()['users']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseOnGetAll(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'users' => null // invalid type
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->getAll('fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGetOne(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'user' => ModelFactory::getTestableUser()->asArray()
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->get(new User(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertInstanceOf(User::class, $response->getData()['user']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseOnGetOne(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'user' => [] // should not be an empty array
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->get(new User(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testCreate(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'user' => ModelFactory::getTestableUser()->asArray()
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->create(new User(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertInstanceOf(User::class, $response->getData()['user']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseOnCreate(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'user' => null // can not be null
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->create(new User(), 'fake user jwt');
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
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->update(new User(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseOnUpdate(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->update(new User(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testDelete(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'user' => ModelFactory::getTestableUser()->asArray()
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->delete(new User(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseOnDelete(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => false, // invalid type
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->delete(new User(), 'fake user jwt');
    }
}