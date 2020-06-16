<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Model\Data;

use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * PostTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Model\Data
 */
class PostTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCommon(): void
    {
        $model = ModelFactory::getTestablePost();

        $actual = Normalizer::getInstance()
            ->normalize($model);

        $expected = [];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupMain(): void
    {
        $normalizer = Normalizer::getInstance();
        $normalizerContext = [
            AbstractNormalizer::GROUPS => [
                'main'
            ]
        ];

        $model = ModelFactory::getTestablePost();

        $actual = $normalizer->normalize($model, $normalizerContext);

        $permissions = [];
        foreach ($model->getPermissions() as $permission) {
            $permissions[] = $normalizer->normalize($permission, $normalizerContext);
        }

        $expected = [
            'uid' => $model->getUid(),
            'name' => $model->getName(),
            'shortName' => $model->getShortName(),
            'permissions' => $permissions
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCreate(): void
    {
        $normalizer = Normalizer::getInstance();
        $normalizerContext = [
            AbstractNormalizer::GROUPS => [
                'create',
            ],
        ];

        $model = ModelFactory::getTestablePost();

        $actual = $normalizer->normalize($model, $normalizerContext);

        $permissions = [];
        foreach ($model->getPermissions() as $permission) {
            $permissions[] = $normalizer->normalize($permission, $normalizerContext);
        }

        $expected = [
            'name' => $model->getName(),
            'shortName' => $model->getShortName(),
            'permissions' => $permissions
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupUpdate(): void
    {
        $serializer = Normalizer::getInstance();

        $model = ModelFactory::getTestablePost();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'update',
                ],
            ]
        );

        $expected = [
            'name' => $model->getName(),
            'shortName' => $model->getShortName(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
