<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User\Lead;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\User\Lead\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;

/**
 * Class ApiAddLeadsTest
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\User\Lead
 */
class ApiAddLeadsTest extends EndpointTest
{
    /**
     * @throws ClientException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_USER_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->addLeads(
            ModelFactory::getTestableUser()->setUid(null),
            [ModelFactory::getTestablePost()],
            JwtFactory::create()
        );
    }

    /**
     * @throws ClientException
     */
    public function testThrowOnInvalidLeadsParameterAddLeads(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::INVALID_MODEL);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->addLeads(ModelFactory::getTestableUser(), ['not a lead'], JwtFactory::create());
    }

    /**
     * @throws ClientException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->addLeads(ModelFactory::getTestableUser(), [ModelFactory::getTestablePost(),], JwtFactory::create());
    }
}
