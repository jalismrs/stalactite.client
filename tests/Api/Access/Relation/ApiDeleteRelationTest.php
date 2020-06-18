<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\Relation;

use Jalismrs\Stalactite\Client\Access\Relation\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Tests\Factory\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;

/**
 * ApiDeleteRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\Relation
 */
class ApiDeleteRelationTest extends EndpointTest
{
    /**
     * @throws ClientException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(AccessServiceException::class);
        $this->expectExceptionCode(AccessServiceException::MISSING_DOMAIN_RELATION_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->delete(ModelFactory::getTestableDomainUserRelation()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->delete(ModelFactory::getTestableDomainUserRelation(), JwtFactory::create());
    }
}
