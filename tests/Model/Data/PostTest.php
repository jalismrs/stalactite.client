<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Model\Data;

use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * PostTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Data\Model
 */
class PostTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupCommon(): void
    {
        $model = ModelFactory::getTestablePost();

        $actual = Serializer::getInstance()->normalize($model);

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
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
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
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
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
