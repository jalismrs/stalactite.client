<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Domain;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointDeleteTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Domain
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Domain\Service
 */
class EndpointDeleteTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowLacksUid(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::MISSING_DOMAIN_UID);

        $systemUnderTest = $this->createSystemUnderTest();

        $systemUnderTest->delete(
            TestableModelFactory::getTestableDomain()
                ->setUid(null),
            JwtFactory::create()
        );
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);

        $systemUnderTest->delete(
            TestableModelFactory::getTestableDomain(),
            JwtFactory::create()
        );
    }
}
