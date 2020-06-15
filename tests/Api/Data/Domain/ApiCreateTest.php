<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\Domain;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Domain\Service;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class ApiCreateTest
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\Domain
 */
class ApiCreateTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     */
    public function testCreate(): void
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
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->createDomain(ModelFactory::getTestableDomain()->setUid(null), JwtFactory::create());

        self::assertInstanceOf(Domain::class, $response->getBody());
    }

    /**
     * @throws ClientException
     * @throws NormalizerException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->createDomain(ModelFactory::getTestableDomain()->setUid(null), JwtFactory::create());
    }
}
