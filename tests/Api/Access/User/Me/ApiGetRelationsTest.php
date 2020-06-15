<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\User\Me;

use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\User\Me\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\Factory\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetRelationsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\User\Me
 */
class ApiGetRelationsTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     */
    public function testGetRelations(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode([
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestableDomainUserRelation(),
                            [
                                AbstractNormalizer::GROUPS => ['main'],
                                AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']
                            ]
                        )
                ], JSON_THROW_ON_ERROR)
            )
        );

        $response = $mockService->getRelations(JwtFactory::create());

        self::assertContainsOnlyInstancesOf(DomainUserRelation::class, $response->getBody());
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->getRelations(JwtFactory::create());
    }
}
