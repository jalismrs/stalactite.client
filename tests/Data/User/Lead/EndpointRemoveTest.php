<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User\Lead;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointRemoveTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\User\Lead
 *
 * @covers \Jalismrs\Stalactite\Client\Data\User\Lead\Service
 */
class EndpointRemoveTest extends
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
        $this->expectExceptionCode(DataServiceException::MISSING_USER_UID);

        $systemUnderTest = $this->createSystemUnderTest();

        $systemUnderTest->remove(
            TestableModelFactory::getTestableUser()
                ->setUid(null),
            [TestableModelFactory::getTestablePost()],
            JwtFactory::create()
        );
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowOnInvalidPostsParameterRemoveLeads(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::INVALID_MODEL);

        $systemUnderTest = $this->createSystemUnderTest();

        $systemUnderTest->remove(
            TestableModelFactory::getTestableUser(),
            ['not a lead'],
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

        $systemUnderTest->remove(
            TestableModelFactory::getTestableUser(),
            [TestableModelFactory::getTestablePost()],
            JwtFactory::create()
        );
    }
}
