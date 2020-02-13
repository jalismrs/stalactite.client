<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Model\Access;

use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * DomainUserRelationTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Access\Model
 */
class DomainUserRelationTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupCommon(): void
    {
        $model = ModelFactory::getTestableDomainUserRelation();

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

        $model = ModelFactory::getTestableDomainUserRelation();

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
            'domain' => $serializer->normalize(
                $model->getDomain(),
                [
                    AbstractNormalizer::GROUPS => [
                        'main',
                    ],
                ]
            ),
            'user' => $serializer->normalize(
                $model->getUser(),
                [
                    AbstractNormalizer::GROUPS => [
                        'main',
                    ],
                ]
            ),
        ];

        self::assertEquals($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupIgnoreDomain(): void
    {
        $serializer = Serializer::getInstance();

        $model = ModelFactory::getTestableDomainUserRelation();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'main',
                ],
                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                    'domain',
                ],
            ]
        );

        $expected = [
            'uid' => $model->getUid(),
            'user' => $serializer->normalize(
                $model->getUser(),
                [
                    AbstractNormalizer::GROUPS => [
                        'main',
                    ],
                ]
            ),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupMainIgnoreUser(): void
    {
        $serializer = Serializer::getInstance();

        $model = ModelFactory::getTestableDomainUserRelation();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'main',
                ],
                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                    'user',
                ],
            ]
        );

        $expected = [
            'uid' => $model->getUid(),
            'domain' => $serializer->normalize(
                $model->getDomain(),
                [
                    AbstractNormalizer::GROUPS => [
                        'main',
                    ],
                ]
            ),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
