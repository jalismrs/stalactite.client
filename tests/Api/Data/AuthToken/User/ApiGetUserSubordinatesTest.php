<?php

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\AuthToken\User;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\AuthToken\User\Service;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ApiGetUserSubordinatesTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws SerializerException
     */
    public function testGetUserSubordinates(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode([
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestableUser(),
                            [
                                AbstractNormalizer::GROUPS => ['main']
                            ]
                        )
                ], JSON_THROW_ON_ERROR)
            )
        );

        $response = $mockService->getUserSubordinates('fake user uid', 'fake user jwt');

        self::assertContainsOnlyInstancesOf(User::class, $response->getBody());
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->getUserSubordinates('fake user uid', 'fake user jwt');
    }
}