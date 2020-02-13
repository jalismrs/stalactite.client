<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\AuthToken\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\Customer\Service;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetByEmailAndGoogleIdTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Data\AuthToken\Customer
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
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     * @throws RequestException
     */
    public function testGetByEmailAndGoogleId(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => null,
                        'customer' => Serializer::getInstance()
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
        );

        $customerModel = ModelFactory::getTestableCustomer();

        $response = $mockService->getByEmailAndGoogleId(
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
     * @throws SerializerException
     * @throws RequestException
     */
    public function testThrowExceptionOnInvalidResponseGetByEmailAndGoogleId(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => null,
                        'customer' => 'invalid-customer'
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $customerModel = ModelFactory::getTestableCustomer();

        $mockService->getByEmailAndGoogleId(
            $customerModel->getEmail(),
            $customerModel->getGoogleId(),
            'fake API auth token'
        );
    }
}
