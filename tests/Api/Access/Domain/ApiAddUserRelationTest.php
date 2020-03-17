<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\Domain;

use Jalismrs\Stalactite\Client\Access\Domain\Service;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiAddUserRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\Domain
 */
class ApiAddUserRelationTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws SerializerException
     */
    public function testAddUserRelation(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            ModelFactory::getTestableDomainUserRelation(),
                            [
                                AbstractNormalizer::GROUPS => ['main']
                            ]
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->addUserRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );

        static::assertInstanceOf(DomainUserRelation::class, $response->getBody());
    }

    /**
     * @throws ClientException
     */
    public function testThrowDomainLacksUid(): void
    {
        $this->expectException(AccessServiceException::class);
        $this->expectExceptionCode(AccessServiceException::MISSING_DOMAIN_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->addUserRelation(
            DataTestModelFactory::getTestableDomain()->setUid(null),
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }

    /**
     * @throws ClientException
     */
    public function testThrowUserLacksUid(): void
    {
        $this->expectException(AccessServiceException::class);
        $this->expectExceptionCode(AccessServiceException::MISSING_USER_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->addUserRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableUser()->setUid(null),
            'fake user jwt'
        );
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());

        $mockService->addUserRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
}
