<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\Relation;

use Jalismrs\Stalactite\Client\Access\Relation\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * ApiDeleteRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\Relation
 */
class ApiDeleteRelationTest extends
    EndpointTest
{
    /**
     * testDeleteRelation
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
    public function testDeleteRelation() : void
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
        
        $response = $mockService->deleteRelation(
            ModelFactory::getTestableDomainUserRelation(),
            'fake user jwt'
        );
        
        static::assertTrue($response->isSuccess());
        static::assertNull($response->getError());
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
        $this->expectExceptionMessage('DomainRelation lacks a uid');
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
    
        $mockService->deleteRelation(
            ModelFactory::getTestableDomainUserRelation()->setUid(null),
            'fake user jwt'
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
    
        $mockService->deleteRelation(
            ModelFactory::getTestableDomainUserRelation(),
            'fake user jwt'
        );
    }
}
