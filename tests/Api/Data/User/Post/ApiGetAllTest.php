<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User\Post;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\User\Post\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetAllTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\User\Post
 */
class ApiGetAllTest extends
    TestCase
{
    /**
     * testGetAll
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testGetAll() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => null,
                        'posts'   => [
                            Serializer::getInstance()
                                      ->normalize(
                                          ModelFactory::getTestablePost(),
                                          [
                                              AbstractNormalizer::GROUPS => [
                                                  'main',
                                              ],
                                          ]
                                      )
                        ]
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $response = $mockService->getAllPosts(
            ModelFactory::getTestableUser(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            Post::class,
            $response->getData()['posts']
        );
    }
    
    /**
     * testThrowOnInvalidResponseGetAll
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testThrowOnInvalidResponseGetAll() : void
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
                        'posts'   => Serializer::getInstance()
                                               ->normalize(
                                                   ModelFactory::getTestablePost(),
                                                   [
                                                       AbstractNormalizer::GROUPS => [
                                                           'main',
                                                       ],
                                                   ]
                                               )
                        // invalid type
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $mockService->getAllPosts(
            ModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
}
