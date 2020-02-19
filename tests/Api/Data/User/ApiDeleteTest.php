<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\User\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Api\ApiAbstract;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiDeleteTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\User
 */
class ApiDeleteTest extends
    ApiAbstract
{
    /**
     * testDelete
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
    public function testDelete() : void
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
        
        $response = $mockService->deleteUser(
            ModelFactory::getTestableUser()
                        ->getUid(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
    }
    
    /**
     * testRequestMethodCalledOnce
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws RuntimeException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockService = new Service($this->createMockClient());
    
        $mockService->deleteUser(
            ModelFactory::getTestableUser()
                        ->getUid(),
            'fake user jwt'
        );
    }
}
