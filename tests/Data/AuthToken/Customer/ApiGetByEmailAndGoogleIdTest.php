<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\AuthToken\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\Customer\Client;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetByEmailAndGoogleIdTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\AuthToken\Customer
 */
class ApiGetByEmailAndGoogleIdTest extends
    TestCase
{
    /**
     * testGetByEmailAndGoogleId
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
    public function testGetByEmailAndGoogleId() : void
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
                                'customer' => $serializer->normalize(
                                    ModelFactory::getTestableCustomer(),
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
        
        $customerModel = ModelFactory::getTestableCustomer();
        
        $response = $mockAPIClient->getByEmailAndGoogleId(
            $customerModel->getEmail(),
            $customerModel->getGoogleId(),
            'fake API auth token'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            Customer::class,
            $response->getData()['customer']
        );
    }
    
    /**
     * testThrowExceptionOnInvalidResponseGetByEmailAndGoogleId
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetByEmailAndGoogleId() : void
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
                                'customer' => []
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $customerModel = ModelFactory::getTestableCustomer();
        
        $mockAPIClient->getByEmailAndGoogleId(
            $customerModel->getEmail(),
            $customerModel->getGoogleId(),
            'fake API auth token'
        );
    }
}
