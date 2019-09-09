<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement\AuthToken;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\AuthToken\UserClient;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class UserClientTest extends TestCase
{
    /**
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function testGetByEmailAndGoogleId(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'user' => [
                    'uid' => 'azertyuiop',
                    'type' => 'user',
                    'privilege' => 'user'
                ]
            ]))
        ]);

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $user = new User();
        $user->setEmail('goodmorning@hello.hi')->setGoogleId('0123456789');

        $response = $mockAPIClient->getByEmailAndGoogleId($user, 'fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertTrue(isset($response->getData()['user']));
        $this->assertTrue(isset($response->getData()['user']['uid']));
        $this->assertTrue(isset($response->getData()['user']['type']));
        $this->assertTrue(isset($response->getData()['user']['privilege']));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetByEmailAndGoogleId(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'user' => [] // invalid user
            ]))
        ]);

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $user = new User();
        $user->setEmail('goodmorning@hello.hi')->setGoogleId('0123456789');

        $mockAPIClient->getByEmailAndGoogleId($user, 'fake user jwt');
    }
}