<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * UserTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Model\User
 */
class UserTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCommon(): void
    {
        $model = TestableModelFactory::getTestableUser();

        $actual = Normalizer::getInstance()
            ->normalize($model);

        $expected = [];

        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupMain(): void
    {
        $serializer = Normalizer::getInstance();

        $model = TestableModelFactory::getTestableUser();

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
            'admin' => $model->isAdmin(),
        ];

        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupMin(): void
    {
        $serializer = Normalizer::getInstance();

        $model = TestableModelFactory::getTestableUser();

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
            'admin' => $model->isAdmin(),
        ];

        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCreate(): void
    {
        $serializer = Normalizer::getInstance();

        $model = TestableModelFactory::getTestableUser();

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

        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupUpdate(): void
    {
        $serializer = Normalizer::getInstance();

        $model = TestableModelFactory::getTestableUser();

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

        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupUpdateMe(): void
    {
        $serializer = Normalizer::getInstance();

        $model = TestableModelFactory::getTestableUser();

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

        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }

    /**
     * @param array $posts
     * @param int $expectedCount
     *
     * @dataProvider getPosts
     */
    public function testSetPosts(
        array $posts,
        int $expectedCount
    ): void
    {
        $user = new User();
        $user->setPosts($posts);

        self::assertCount(
            $expectedCount,
            $user->getPosts()
        );
        self::assertContainsOnlyInstancesOf(
            Post::class,
            $user->getPosts()
        );
    }

    /**
     * @param array $leads
     * @param int $expectedCount
     *
     * @dataProvider getPosts
     */
    public function testSetLeads(
        array $leads,
        int $expectedCount
    ): void
    {
        $user = new User();
        $user->setLeads($leads);

        self::assertCount(
            $expectedCount,
            $user->getLeads()
        );
        self::assertContainsOnlyInstancesOf(
            Post::class,
            $user->getLeads()
        );
    }

    /**
     * @return array
     */
    public function getPosts(): array
    {
        return [
            [
                [
                    new Post(),
                    new Post(),
                    new Post(),
                ],
                3,
            ],
            [
                [
                    'this',
                    'is',
                    'a',
                    'post',
                    new Post(),
                ],
                1,
            ],
            [
                [
                    'not',
                    'a',
                    'post',
                ],
                0,
            ],
        ];
    }

    /**
     * @param User $user
     * @param array $posts
     * @param bool $hasExplicitPermission
     * @param bool $hasPermission
     *
     * @dataProvider getUserProvider
     */
    public function testPermissions(
        User $user,
        array $posts,
        bool $hasExplicitPermission,
        bool $hasPermission
    ): void
    {
        $user->setPosts($posts);
        $permission = TestableModelFactory::getTestablePermission();

        self::assertSame(
            $hasExplicitPermission,
            $user->hasExplicitPermission((string)$permission),
            'explicit'
        );
        self::assertSame(
            $hasPermission,
            $user->hasPermission((string)$permission),
            'global'
        );
    }

    /**
     * @return array|array[]
     */
    public function getUserProvider(): array
    {
        $user = TestableModelFactory::getTestableUser();
        $admin = TestableModelFactory::getTestableUser()
            ->setAdmin(true);

        $postWithoutPermission = TestableModelFactory::getTestablePost();
        $postWithPermission = TestableModelFactory::getTestablePost()
            ->addPermission(TestableModelFactory::getTestablePermission());

        return [
            [
                $user,
                [],
                false,
                false,
            ],
            [
                $user,
                [$postWithoutPermission],
                false,
                false,
            ],
            [
                $user,
                [$postWithPermission],
                true,
                true,
            ],
            [
                $user,
                [
                    $postWithoutPermission,
                    $postWithPermission,
                ],
                true,
                true,
            ],
            [
                $admin,
                [],
                false,
                true,
            ],
            [
                $admin,
                [$postWithoutPermission],
                false,
                true,
            ],
            [
                $admin,
                [$postWithPermission],
                true,
                true,
            ],
            [
                $admin,
                [
                    $postWithoutPermission,
                    $postWithPermission,
                ],
                true,
                true,
            ],
        ];
    }
}
