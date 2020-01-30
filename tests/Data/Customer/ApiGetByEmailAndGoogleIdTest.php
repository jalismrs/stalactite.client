<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Customer\Service;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetByEmailAndGoogleIdTest
 *
 * @packageJalismrs\Stalactite\Service\Tests\Data\Customer
 */
class ApiGetByEmailAndGoogleIdTest extends
    TestCase
{
    /**
     * testGetByEmailAndGoogleId
     *
     * @return void
     *
     * @throws ClientException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGetByEmailAndGoogleId() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'  => true,
                                'error'    => null,
                                'customer' => $mockClient
                                    ->getSerializer()
                                    ->normalize(
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
        
        $response = $mockService->getByEmailAndGoogleId(
            $customerModel->getEmail(),
            $customerModel->getGoogleId(),
            'fake user jwt'
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
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
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
        
        $mockService->getByEmailAndGoogleId(
            $customerModel->getEmail(),
            $customerModel->getGoogleId(),
            'fake user jwt'
        );
    }
}
