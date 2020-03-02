<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\Domain;

use Jalismrs\Stalactite\Client\Access\Domain\Service;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiAddUserRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\Domain
 */
class ApiAddUserRelationTest extends
    EndpointTest
{
    /**
     * testAddUserRelation
     *
     * @return void
     *
     * @throws ClientException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testAddUserRelation() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success'  => true,
                        'error'    => null,
                        'relation' => Serializer::getInstance()
                                                ->normalize(
                                                    ModelFactory::getTestableDomainUserRelation(),
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
        
        $response = $mockService->addUserRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
        
        static::assertTrue($response->isSuccess());
        static::assertNull($response->getError());
        static::assertInstanceOf(DomainUserRelation::class, $response->getData()['relation']);
    }
    
    /**
     * testThrowDomainLacksUid
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testThrowDomainLacksUid() : void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('Domain lacks a uid');
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->addUserRelation(
            DataTestModelFactory::getTestableDomain()->setUid(null),
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
    
    /**
     * testThrowUserLacksUid
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testThrowUserLacksUid() : void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('User lacks a uid');
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        $mockService->addUserRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableUser()->setUid(null),
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
    
        $mockService->addUserRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
}
