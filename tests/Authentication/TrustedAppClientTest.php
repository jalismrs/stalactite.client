<?php

namespace jalismrs\Stalactite\Client\Test\Authentication;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\Authentication\Client;
use jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use jalismrs\Stalactite\Client\ClientException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class TrustedAppClientTest extends TestCase
{
    /**
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function testGetAllTrustedApps(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'trustedApps' => [ModelFactory::getTestableTrustedApp()->asArray()]
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $response = $mockClient->trustedApps()->getAll('fake user jwt');

        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertContainsOnlyInstancesOf(TrustedApp::class, $response->getData()['trustedApps']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testInvalidResponseFromApiWhileGettingTrustedApps(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'trustedApps' => 'wrong response type'
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $mockClient->trustedApps()->getAll('fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGetTrustedApp(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'trustedApp' => ModelFactory::getTestableTrustedApp()->asArray()
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $response = $mockClient->trustedApps()->get(ModelFactory::getTestableTrustedApp()->getUid(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertInstanceOf(TrustedApp::class, $response->getData()['trustedApp']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testInvalidResponseFromApiWhileGettingTrustedApp(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'trustedApps' => 'wrong response type'
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $mockClient->trustedApps()->get(ModelFactory::getTestableTrustedApp()->getUid(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testCreateTrustedApp(): void
    {
        $ta = ModelFactory::getTestableTrustedApp();
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'trustedApp' => array_merge($ta->asArray(), ['resetToken' => $ta->getResetToken()])
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $response = $mockClient->trustedApps()->create(ModelFactory::getTestableTrustedApp(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertInstanceOf(TrustedApp::class, $response->getData()['trustedApp']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnCreateTrustedApp(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'trustedApp' => []
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $mockClient->trustedApps()->create(ModelFactory::getTestableTrustedApp(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testUpdateTrustedApp(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $response = $mockClient->trustedApps()->update(ModelFactory::getTestableTrustedApp(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnUpdateTrustedApp(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $mockClient->trustedApps()->update(ModelFactory::getTestableTrustedApp(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testDeleteTrustedApp(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $ta = ModelFactory::getTestableTrustedApp();

        $response = $mockClient->trustedApps()->delete($ta->getUid(), $ta->getResetToken(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnDeleteTrustedApp(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $ta = ModelFactory::getTestableTrustedApp();

        $mockClient->trustedApps()->delete($ta->getUid(), $ta->getResetToken(), 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testResetTrustedAppAuthToken(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'trustedApp' => ModelFactory::getTestableTrustedApp()->asArray()
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $response = $mockClient->trustedApps()->resetAuthToken(ModelFactory::getTestableTrustedApp(), 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertInstanceOf(TrustedApp::class, $response->getData()['trustedApp']);
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnResetTrustedAppAuthToken(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $mockClient->trustedApps()->resetAuthToken(ModelFactory::getTestableTrustedApp(), 'fake user jwt');
    }
}