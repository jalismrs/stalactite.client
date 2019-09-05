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

class TrustedAppTest extends TestCase
{
    private static function generateTrustedApp(): TrustedApp
    {
        $trustedApp = new TrustedApp();
        $trustedApp->setName('fake name')
            ->setUid('azertyuiop')
            ->setGoogleOAuthClientId('qsdfghjklm')
            ->setAuthToken('aqwzsxedcrfv')
            ->setResetToken('tgbyhnujikol');

        return $trustedApp;
    }

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
                'trustedApps' => [[
                    'uid' => 'azertyuiop',
                    'name' => 'fake trusted app',
                    'authToken' => 'qsdfghjklm',
                    'googleOAuthClientId' => 'wxcvbn'
                ]]
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockClient->trustedApps()->getAll('fake user jwt'));
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
                'trustedApps' => [
                    'uid' => 'azertyuiop',
                    'name' => 'fake trusted app',
                    'authToken' => 'qsdfghjklm',
                    'googleOAuthClientId' => 'wxcvbn'
                ]
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockClient->trustedApps()->get('fake trusted app uid', 'fake user jwt'));
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

        $mockClient->trustedApps()->get('fake trusted app uid', 'fake user jwt');
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testCreateTrustedApp(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'trustedApp' => [
                    'uid' => 'azertyuiop',
                    'name' => 'fake name',
                    'googleOAuthClientId' => 'qsdfghjklm',
                    'authToken' => 'wxcvbn',
                    'resetToken' => '1234567890'
                ]
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockClient->trustedApps()->create(self::generateTrustedApp(), 'fake user jwt'));
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
                'error' => null
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $mockClient->trustedApps()->create(self::generateTrustedApp(), 'fake user jwt');
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

        $this->assertIsArray($mockClient->trustedApps()->update(self::generateTrustedApp(), 'fake user jwt'));
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

        $mockClient->trustedApps()->update(self::generateTrustedApp(), 'fake user jwt');
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

        $this->assertIsArray($mockClient->trustedApps()->delete(self::generateTrustedApp(), 'fake user jwt'));
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

        $mockClient->trustedApps()->delete(self::generateTrustedApp(), 'fake user jwt');
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
                'trustedApp' => [
                    'uid' => 'azertyuiop',
                    'name' => 'fake name',
                    'googleOAuthClientId' => 'qsdfghjklm',
                    'authToken' => 'wxcvbn'
                ]
            ]))
        ]);

        $mockClient = new Client('http://fakeClient');
        $mockClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockClient->trustedApps()->resetAuthToken(self::generateTrustedApp(), 'fake user jwt'));
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

        $mockClient->trustedApps()->resetAuthToken(self::generateTrustedApp(), 'fake user jwt');
    }
}