<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Access\Domain\Client;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiAddCustomerRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access\Domain
 */
class ApiAddCustomerRelationTest extends
    TestCase
{
    /**
     * testAddCustomerRelation
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testAddCustomerRelation() : void
    {
        $serializer = Serializer::create();
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'  => true,
                                'error'    => null,
                                'relation' => $serializer->normalize(
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
                ]
            )
        );
        
        $response = $mockAPIClient->addCustomerRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
        static::assertTrue($response->isSuccess());
        static::assertNull($response->getError());
        static::assertInstanceOf(DomainCustomerRelation::class, $response->getData()['relation']);
    }
    
    /**
     * testThrowExceptionOnInvalidResponseAddCustomerRelation
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseAddCustomerRelation() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'  => true,
                                'error'    => null,
                                'relation' => []
                                // wrong type
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $mockAPIClient->addCustomerRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
    }
}
