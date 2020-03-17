<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetAllTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Authentication\TrustedApp
 */
class ApiGetAllTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws SerializerException
     */
    public function testGetAll(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        Normalizer::getInstance()
                            ->normalize(
                                ModelFactory::getTestableTrustedApp(),
                                [
                                    AbstractNormalizer::GROUPS => ['main']
                                ]
                            )
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->getAllTrustedApps('fake user jwt');

        self::assertContainsOnlyInstancesOf(TrustedApp::class, $response->getBody());
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->getAllTrustedApps('fake user jwt');
    }
}
