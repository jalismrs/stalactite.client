<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Model;

use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * DomainUserRelationTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Access\Model
 */
class DomainCustomerRelationTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupCommon(): void
    {
        $model = ModelFactory::getTestableDomainCustomerRelation();

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

        $model = ModelFactory::getTestableDomainCustomerRelation();

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
            'customer' => $serializer->normalize(
                $model->getCustomer(),
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
    public function testGroupIgnoreDomain(): void
    {
        $serializer = Serializer::getInstance();

        $model = ModelFactory::getTestableDomainCustomerRelation();

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
            'customer' => $serializer->normalize(
                $model->getCustomer(),
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
    public function testGroupMainIgnoreCustomer(): void
    {
        $serializer = Serializer::getInstance();

        $model = ModelFactory::getTestableDomainCustomerRelation();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'main',
                ],
                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                    'customer',
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
