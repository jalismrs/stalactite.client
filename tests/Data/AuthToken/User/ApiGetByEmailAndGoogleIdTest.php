<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\AuthToken\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\User\Service;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetByEmailAndGoogleIdTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Data\AuthToken\User
 */
class ApiGetByEmailAndGoogleIdTest extends
    TestCase
{
    /**
     * testGetByEmailAndGoogleId
     *
     * @return void
     *
     * @throws ClientException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGetByEmailAndGoogleId(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => null,
                        'user' => Serializer::getInstance()
                            ->normalize(
                                ModelFactory::getTestableUser(),
                                [
                                    AbstractNormalizer::GROUPS => [
                                        'main',
                                    ],
                                ]
                            )
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->getByEmailAndGoogleId(
            'goodmorning@hello.hi',
            '0123456789',
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            User::class,
            $response->getData()['user']
        );
    }

    /**
     * testThrowExceptionOnInvalidResponseGetByEmailAndGoogleId
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetByEmailAndGoogleId(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => null,
                        'user' => []
                        // invalid user
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $mockService->getByEmailAndGoogleId(
            'goodmorning@hello.hi',
            '0123456789',
            'fake user jwt'
        );
    }
}
