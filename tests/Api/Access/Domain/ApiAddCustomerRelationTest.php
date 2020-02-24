<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\Domain;

use Jalismrs\Stalactite\Client\Access\Domain\Service;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Api\ApiAbstract;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiAddCustomerRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\Domain
 */
class ApiAddCustomerRelationTest extends
    ApiAbstract
{
    /**
     * testAddCustomerRelation
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
    public function testAddCustomerRelation() : void
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
                                                    ModelFactory::getTestableDomainCustomerRelation(),
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
        
        $response = $mockService->addCustomerRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
        
        static::assertTrue($response->isSuccess());
        static::assertNull($response->getError());
        static::assertInstanceOf(DomainCustomerRelation::class, $response->getData()['relation']);
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
        
        $mockService->addCustomerRelation(
            DataTestModelFactory::getTestableDomain()->setUid(null),
            DataTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
    }
    
    /**
     * testThrowCustomerLacksUid
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function testThrowCustomerLacksUid() : void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('Customer lacks a uid');
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        $mockService->addCustomerRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableCustomer()->setUid(null),
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
    
        $mockService->addCustomerRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
    }
}
