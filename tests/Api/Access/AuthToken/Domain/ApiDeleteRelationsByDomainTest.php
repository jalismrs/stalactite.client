<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\AuthToken\Domain;

use Jalismrs\Stalactite\Client\Access\AuthToken\Domain\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;

/**
 * ApiDeleteRelationsByDomainTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\AuthToken\Domain
 */
class ApiDeleteRelationsByDomainTest extends EndpointTest
{
    /**
     * @throws ClientException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(AccessServiceException::class);
        $this->expectExceptionCode(AccessServiceException::MISSING_DOMAIN_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->deleteRelationsByDomain(ModelFactory::getTestableDomain()->setUid(null), 'fake API auth token');
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->deleteRelationsByDomain(ModelFactory::getTestableDomain(), 'fake API auth token');
    }
}
