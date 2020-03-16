<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\AuthToken\User;

use Jalismrs\Stalactite\Client\Access\AuthToken\User\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;

/**
 * ApiDeleteRelationsByUserTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\AuthToken\User
 */
class ApiDeleteRelationsByUserTest extends EndpointTest
{

    /**
     * @throws ClientException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(AccessServiceException::class);
        $this->expectExceptionCode(AccessServiceException::MISSING_USER_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->deleteRelationsByUser(ModelFactory::getTestableUser()->setUid(null), 'fake API auth token');
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->deleteRelationsByUser(ModelFactory::getTestableUser(), 'fake API auth token');
    }
}
