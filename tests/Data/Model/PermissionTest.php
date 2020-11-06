<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class PermissionTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Model\Permission
 */
class PermissionTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCommon(): void
    {
        $model = TestableModelFactory::getTestablePermission();

        $actual = Normalizer::getInstance()
            ->normalize($model);

        $expected = [

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
    public function testGroupMain(): void
    {
        $serializer = Normalizer::getInstance();

        $model = TestableModelFactory::getTestablePermission();

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
            'scope' => $model->getScope(),
            'resource' => $model->getResource(),
            'operation' => $model->getOperation(),
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

        $model = TestableModelFactory::getTestablePermission();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'create',
                ],
            ]
        );

        $expected = [
            'scope' => $model->getScope(),
            'resource' => $model->getResource(),
            'operation' => $model->getOperation(),
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

        $model = TestableModelFactory::getTestablePermission();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'update',
                ],
            ]
        );

        $expected = [
            'scope' => $model->getScope(),
            'resource' => $model->getResource(),
            'operation' => $model->getOperation(),
        ];

        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }

    /**
     * testToString
     *
     * @return void
     */
    public function testToString(): void
    {
        $model = TestableModelFactory::getTestablePermission();

        self::assertSame(
            "scope.resource.operation",
            (string)$model
        );
    }
}
