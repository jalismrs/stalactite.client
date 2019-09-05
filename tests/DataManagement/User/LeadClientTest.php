<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\Post;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\User\LeadClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class LeadClientTest extends TestCase
{
    /**
     * @return User
     */
    private static function getTestableUser(): User
    {
        $user = new User();
        $user->setUid('azertyuiop');

        return $user;
    }

    /**
     * @return Post
     */
    private static function getTestableLead(): Post
    {
        $post = new Post();
        $post->setShortName('aze')->setPrivilege('user')->setRank(1)->setName('azerty')->setUid('azertyuiop');

        return $post;
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
                'leads' => [self::getTestableLead()->asArray()]
            ]))
        ]);

        $mockAPIClient = new LeadClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->getAll(self::getTestableUser(), 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseGetAll(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'leads' => self::getTestableLead()->asArray() // invalid type
            ]))
        ]);

        $mockAPIClient = new LeadClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->getAll(self::getTestableUser(), 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testAddPosts(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ]))
        ]);

        $mockAPIClient = new LeadClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->addLeads(self::getTestableUser(), [self::getTestableLead()], 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseAddPosts(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ]))
        ]);

        $mockAPIClient = new LeadClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->addLeads(self::getTestableUser(), [self::getTestableLead()], 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidPostsParameterAddPosts(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ]))
        ]);

        $mockAPIClient = new LeadClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->addLeads(self::getTestableUser(), [self::getTestableLead()->asArray()], 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testRemovePosts(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ]))
        ]);

        $mockAPIClient = new LeadClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->removeLeads(self::getTestableUser(), [self::getTestableLead()], 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseRemovePosts(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => false // invalid type
            ]))
        ]);

        $mockAPIClient = new LeadClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->removeLeads(self::getTestableUser(), [self::getTestableLead()], 'fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidPostsParameterRemovePosts(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null
            ]))
        ]);

        $mockAPIClient = new LeadClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->removeLeads(self::getTestableUser(), [self::getTestableLead()->asArray()], 'fake user jwt'));
    }
}