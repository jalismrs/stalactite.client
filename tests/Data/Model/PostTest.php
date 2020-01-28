<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * PostTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 */
class PostTest extends
    TestCase
{
    /**
     * testGroupCommon
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGroupCommon(): void
    {
        $serializer = Serializer::getInstance();

        $model = ModelFactory::getTestablePost();

        $actual = $serializer->normalize($model);

        $expected = [];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * testGroupMain
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGroupMain(): void
    {
        $serializer = Serializer::getInstance();

        $model = ModelFactory::getTestablePost();

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
            'name' => $model->getName(),
            'shortName' => $model->getShortName(),
            'adminAccess' => $model->hasAdminAccess(),
            'allowAccess' => $model->allowAccess(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * testGroupCreate
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGroupCreate(): void
    {
        $serializer = Serializer::getInstance();

        $model = ModelFactory::getTestablePost();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'create',
                ],
            ]
        );

        $expected = [
            'access' => $model->allowAccess(),
            'admin' => $model->hasAdminAccess(),
            'name' => $model->getName(),
            'shortName' => $model->getShortName(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * testGroupUpdate
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGroupUpdate(): void
    {
        $serializer = Serializer::getInstance();

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
            'access' => $model->allowAccess(),
            'admin' => $model->hasAdminAccess(),
            'name' => $model->getName(),
            'shortName' => $model->getShortName(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
