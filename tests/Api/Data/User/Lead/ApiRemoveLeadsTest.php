<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User\Lead;

use InvalidArgumentException;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\User\Lead\Service;
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

/**
 * ApiRemoveLeadsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\User\Lead
 */
class ApiRemoveLeadsTest extends
    ApiAbstract
{
    /**
     * testRemoveLeads
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRemoveLeads() : void
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
        
        $response = $mockService->removeLeads(
            ModelFactory::getTestableUser(),
            [
                ModelFactory::getTestablePost()
            ],
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
    }
    
    /**
     * testThrowOnInvalidPostsParameterRemoveLeads
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testThrowOnInvalidPostsParameterRemoveLeads() : void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('Error while getting uids');
    
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
        
        $mockService->removeLeads(
            ModelFactory::getTestableUser(),
            [
                'not a lead'
            ],
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
    
        $mockService->removeLeads(
            ModelFactory::getTestableUser(),
            [
                ModelFactory::getTestablePost()
            ],
            'fake user jwt'
        );
    }
}
