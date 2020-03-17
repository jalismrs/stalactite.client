<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Model\Data;

use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * UserTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Model\Data
 */
class UserTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupCommon(): void
    {
        $model = ModelFactory::getTestableUser();

        $actual = Normalizer::getInstance()
            ->normalize($model);

        $expected = [];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupMain(): void
    {
        $serializer = Normalizer::getInstance();

        $model = ModelFactory::getTestableUser();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'main',
                ],
            ]
        );

        $expected = [
            'uid' => $model->getUid(),
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
            'email' => $model->getEmail(),
            'googleId' => $model->getGoogleId(),
            'admin' => $model->isAdmin()
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupMin(): void
    {
        $serializer = Normalizer::getInstance();

        $model = ModelFactory::getTestableUser();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'min',
                ],
            ]
        );

        $expected = [
            'uid' => $model->getUid(),
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
            'email' => $model->getEmail(),
            'googleId' => $model->getGoogleId(),
            'admin' => $model->isAdmin(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupCreate(): void
    {
        $serializer = Normalizer::getInstance();

        $model = ModelFactory::getTestableUser();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'create',
                ],
            ]
        );

        $expected = [
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
            'email' => $model->getEmail(),
            'admin' => $model->isAdmin(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupUpdate(): void
    {
        $serializer = Normalizer::getInstance();

        $model = ModelFactory::getTestableUser();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'update',
                ],
            ]
        );

        $expected = [
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
            'email' => $model->getEmail(),
            'admin' => $model->isAdmin(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupUpdateMe(): void
    {
        $serializer = Normalizer::getInstance();

        $model = ModelFactory::getTestableUser();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'updateMe',
                ],
            ]
        );

        $expected = [
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @param array $posts
     * @param int $expectedCount
     * @dataProvider getPosts
     */
    public function testSetPosts(array $posts, int $expectedCount): void
    {
        $user = new User();
        $user->setPosts($posts);

        self::assertCount($expectedCount, $user->getPosts());
        self::assertContainsOnlyInstancesOf(Post::class, $user->getPosts());
    }

    /**
     * @param array $leads
     * @param int $expectedCount
     * @dataProvider getPosts
     */
    public function testSetLeads(array $leads, int $expectedCount): void
    {
        $user = new User();
        $user->setLeads($leads);

        self::assertCount($expectedCount, $user->getLeads());
        self::assertContainsOnlyInstancesOf(Post::class, $user->getLeads());
    }

    /**
     * @return array
     */
    public function getPosts(): array
    {
        return [
            [[new Post(), new Post(), new Post()], 3],
            [['this', 'is', 'a', 'post', new Post()], 1],
            [['not', 'a', 'post'], 0]
        ];
    }

    public function testHasAdminPost(): void
    {
        $post = new Post();
        $adminPost = new Post();
        $adminPost->setAdminAccess(true);

        $user = new User();
        $user->setPosts([$post]);

        $adminUser = new User();
        $adminUser->setPosts([$user, $adminPost]);

        self::assertFalse($user->hasAdminPost());
        self::assertTrue($adminUser->hasAdminPost());
    }
}
