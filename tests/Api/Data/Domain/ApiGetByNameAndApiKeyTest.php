<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\Domain;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Domain\Service;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class ApiGetByNameTest
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\Domain
 */
class ApiGetByNameAndApiKeyTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws SerializerException
     */
    public function testGetByName(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestableDomain(),
                            [
                                AbstractNormalizer::GROUPS => ['main']
                            ]
                        ), JSON_THROW_ON_ERROR
                )
            )
        );

        $domain = ModelFactory::getTestableDomain();

        $response = $mockService->getByNameAndApiKey($domain->getName(), $domain->getApiKey(), 'fake user jwt');

        self::assertInstanceOf(Domain::class, $response->getBody());
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $domain = ModelFactory::getTestableDomain();
        $mockService->getByNameAndApiKey($domain->getName(), $domain->getApiKey(), 'fake user jwt');
    }
}
