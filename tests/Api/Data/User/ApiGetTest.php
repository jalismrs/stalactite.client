<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\User\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\User
 */
class ApiGetTest extends
    TestCase
{
    /**
     * testGet
     *
     * @return void
     *
     * @throws ClientException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testGet() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => null,
                        'user'    => Serializer::getInstance()
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
        
        $response = $mockService->getUser(
            ModelFactory::getTestableUser()
                        ->getUid(),
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
     * testThrowOnInvalidResponseGet
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testThrowOnInvalidResponseGet() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => null,
                        'user'    => []
                        // should not be an empty array
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $mockService->getUser(
            ModelFactory::getTestableUser()
                        ->getUid(),
            'fake user jwt'
        );
    }
}