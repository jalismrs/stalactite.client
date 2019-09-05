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
use jalismrs\Stalactite\Client\DataManagement\User\MeClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class MeClientTest extends TestCase
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
    public function testGet(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'me' => self::getTestableUser()->asArray()
            ]))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->get('fake user jwt'));
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
                'me' => [self::getTestableUser()->asArray()] // invalid type
            ]))
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
            ]))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->update(self::getTestableUser(), 'fake user jwt'));
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
            ]))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->update(self::getTestableUser(), 'fake user jwt');
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
            ]))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->addPhoneLine(self::getTestablePhoneLine(), 'fake user jwt'));
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
            ]))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->addPhoneLine(self::getTestablePhoneLine(), 'fake user jwt');
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
            ]))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $phoneLine = self::getTestablePhoneLine()->setType(null);

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
            ]))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->removePhoneLine(self::getTestablePhoneLine(), 'fake user jwt'));
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
            ]))
        );

        $mockAPIClient = new MeClient('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->removePhoneLine(self::getTestablePhoneLine(), 'fake user jwt');
    }
}