<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Customer;

use Jalismrs\Stalactite\Client\Access\Customer\Client;
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
 * ApiGetRelationsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access\Customer
 */
class ApiGetRelationsTest extends
    TestCase
{
    /**
     * testGetRelations
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
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
    public function testGetRelations() : void
    {
        $serializer = Serializer::getInstance();
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'   => true,
                                'error'     => null,
                                'relations' => [
                                    $serializer->normalize(
                                        ModelFactory::getTestableDomainCustomerRelation(),
                                        [
                                            AbstractNormalizer::GROUPS             => [
                                                'main',
                                            ],
                                            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                                                'customer'
                                            ],
                                        ]
                                    )
                                ]
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $response = $mockAPIClient->getRelations(
            DataTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
        
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            DomainCustomerRelation::class,
            $response->getData()['relations']
        );
    }
    
    /**
     * testThrowExceptionOnInvalidResponseGetRelations
     *
     * @return void
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetRelations() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $serializer = Serializer::getInstance();
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'   => true,
                                'error'     => null,
                                'relations' => $serializer->normalize(
                                    ModelFactory::getTestableDomainCustomerRelation(),
                                    [
                                        AbstractNormalizer::GROUPS => [
                                            'main',
                                        ],
                                    ]
                                )
                                // invalid
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $mockAPIClient->getRelations(
            DataTestModelFactory::getTestableCustomer(),
            'fake user jwt'
        );
    }
}
