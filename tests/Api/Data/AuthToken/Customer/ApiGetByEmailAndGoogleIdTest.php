<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\AuthToken\Customer;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\AuthToken\Customer\Service;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class ApiGetByEmailAndGoogleIdTest
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\AuthToken\Customer
 */
class ApiGetByEmailAndGoogleIdTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws SerializerException
     */
    public function testGetByEmailAndGoogleId(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestableCustomer(),
                            [
                                AbstractNormalizer::GROUPS => ['main']
                            ]
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $customerModel = ModelFactory::getTestableCustomer();

        $response = $mockService->getByEmailAndGoogleId($customerModel->getEmail(), $customerModel->getGoogleId(), 'fake API auth token');

        self::assertInstanceOf(Customer::class, $response->getBody());
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $customerModel = ModelFactory::getTestableCustomer();
        $mockService->getByEmailAndGoogleId($customerModel->getEmail(), $customerModel->getGoogleId(), 'fake API auth token');
    }
}
