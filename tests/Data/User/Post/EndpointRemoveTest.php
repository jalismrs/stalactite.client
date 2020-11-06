<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User\Post;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointRemoveTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\User\Post
 *
 * @covers \Jalismrs\Stalactite\Client\Data\User\Post\Service
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
    public function testThrowOnInvalidPostsParameterRemovePosts(): void
    {
        $this->expectException(DataServiceException::class);
        $this->expectExceptionCode(DataServiceException::INVALID_MODEL);

        $systemUnderTest = $this->createSystemUnderTest();

        $systemUnderTest->remove(
            TestableModelFactory::getTestableUser(),
            ['not a post'],
            JwtFactory::create()
        );
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodNotCalledOnEmptyPostList(): void
    {
        $mockClient = $this->createMockClient(false);

        $systemUnderTest = $this->createSystemUnderTest($mockClient);

        $response = $systemUnderTest->remove(
            TestableModelFactory::getTestableUser(),
            [],
            JwtFactory::create()
        );
        self::assertNull($response);
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
