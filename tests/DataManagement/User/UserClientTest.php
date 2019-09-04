<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\CertificationGraduation;
use jalismrs\Stalactite\Client\DataManagement\Model\CertificationType;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneLine;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneType;
use jalismrs\Stalactite\Client\DataManagement\Model\Post;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\User\UserClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class UserClientTest extends TestCase
{
    /**
     * @return User
     */
    private static function getTestableUser(): User
    {
        $user = new User();
        $user->setFirstName('azerty')
            ->setLastName('uiop')
            ->setGender('male')
            ->setEmail('goodMorning@hello.hi')
            ->setPrivilege('user')
            ->setBirthday('2000-01-01')
            ->addPost(self::getTestablePost())
            ->addLead(self::getTestablePost())
            ->addPhoneLine(self::getTestablePhoneLine())
            ->addCertification(self::getTestableCertificationGraduation())
            ->setUid('azertyuiop');

        return $user;
    }

    /**
     * @return Post
     */
    private static function getTestablePost(): Post
    {
        $post = new Post();
        $post->setName('azerty')
            ->setShortName('az')
            ->setPrivilege('user')
            ->setRank(1)
            ->setUid('azertyuiop');

        return $post;
    }

    /**
     * @return CertificationGraduation
     */
    private static function getTestableCertificationGraduation(): CertificationGraduation
    {
        $type = new CertificationType();
        $type->setName('azerty')->setUid('azertyuio');

        $certification = new CertificationGraduation();
        $certification
            ->setDate('2000-01-01')
            ->setType($type)
            ->setUid('azertyuiop');

        return $certification;
    }

    /**
     * @return PhoneLine
     */
    private static function getTestablePhoneLine(): PhoneLine
    {
        $type = new PhoneType();
        $type->setName('azerty')->setUid('azertyuiop');

        $phoneLine = new PhoneLine();
        $phoneLine->setValue('0123456789')->setType($type)->setUid('azertyuiop');

        return $phoneLine;
    }

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
                    self::getTestableUser()->asMinimalArray()
                ]
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->getAll('fake user jwt'));
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

        $this->assertIsArray($mockAPIClient->getAll('fake user jwt'));
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
                'user' => self::getTestableUser()->asArray()
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->get(new User(), 'fake user jwt'));
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

        $this->assertIsArray($mockAPIClient->get(new User(), 'fake user jwt'));
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
                'user' => self::getTestableUser()->asArray()
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->create(new User(), 'fake user jwt'));
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

        $this->assertIsArray($mockAPIClient->create(new User(), 'fake user jwt'));
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

        $this->assertIsArray($mockAPIClient->update(new User(), 'fake user jwt'));
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

        $this->assertIsArray($mockAPIClient->update(new User(), 'fake user jwt'));
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
                'user' => self::getTestableUser()->asArray()
            ]))
        );

        $mockAPIClient = new UserClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->delete(new User(), 'fake user jwt'));
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

        $this->assertIsArray($mockAPIClient->delete(new User(), 'fake user jwt'));
    }
}