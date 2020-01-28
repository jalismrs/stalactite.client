<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Model;

use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * DomainUserRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access\Model
 */
class DomainUserRelationTest extends
    TestCase
{
    /**
     * testGroupCommon
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     */
    public function testGroupCommon(): void
    {
        $serializer = Serializer::getInstance();

        $model = ModelFactory::getTestableDomainUserRelation();

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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
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

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * testGroupIgnoreDomain
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
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
     * testGroupMainIgnoreCustomer
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
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
