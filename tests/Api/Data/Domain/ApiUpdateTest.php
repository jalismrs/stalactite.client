<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\Domain;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Domain\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Factory\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * ApiUpdateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\Domain
 */
class ApiUpdateTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_DOMAIN_UID);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);

        $mockService->update(ModelFactory::getTestableDomain()->setUid(null), JwtFactory::create());
    }

    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockService = new Service($this->createMockClient());
        $mockService->update(ModelFactory::getTestableDomain(), JwtFactory::create());
    }
}
