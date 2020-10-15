<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Customer\Me\Relation;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Customer\Relation\Service;
use Jalismrs\Stalactite\Client\Data\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class EndpointGetRelationsTest extends AbstractTestEndpoint
{
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
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
                            ModelFactory::getTestableDomainCustomerRelation(),
                            [
                                AbstractNormalizer::GROUPS => ['main'],
                                AbstractNormalizer::IGNORED_ATTRIBUTES => ['customer']
                            ]
                        )
                ], JSON_THROW_ON_ERROR)
            )
        );

        $response = $mockService->all(ModelFactory::getTestableCustomer(), JwtFactory::create());

        self::assertContainsOnlyInstancesOf(DomainCustomerRelation::class, $response->getBody());
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->all(ModelFactory::getTestableCustomer(), JwtFactory::create());
    }
}
