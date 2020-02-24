<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\AuthToken\User;

use Jalismrs\Stalactite\Client\Access\AuthToken\User\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Api\ApiAbstract;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * ApiDeleteRelationsByUserTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\AuthToken\User
 */
class ApiDeleteRelationsByUserTest extends
    ApiAbstract
{
    /**
     * testDeleteRelationsByUser
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testDeleteRelationsByUser() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => null
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $response = $mockService->deleteRelationsByUser(
            ModelFactory::getTestableUser(),
            'fake API auth token'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
    }
    
    /**
     * testThrowLacksUid
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testThrowLacksUid() : void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('User lacks a uid');
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        $mockService->deleteRelationsByUser(
            ModelFactory::getTestableUser()->setUid(null),
            'fake API auth token'
        );
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
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockService = new Service($this->createMockClient());
    
        $mockService->deleteRelationsByUser(
            ModelFactory::getTestableUser(),
            'fake API auth token'
        );
    }
}
