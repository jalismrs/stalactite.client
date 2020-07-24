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
 * DomainUserRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Model\Access
 */
class DomainUserRelationTest extends TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCommon(): void
    {
        $model = ModelFactory::getTestableDomainUserRelation();

        $actual = Normalizer::getInstance()->normalize($model);

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
        $serializer = Normalizer::getInstance();

        $model = ModelFactory::getTestableDomainUserRelation();

        $actual = $serializer->normalize(
            $model,
            [AbstractNormalizer::GROUPS => ['main']]
        );

        $expected = [
            'uid' => $model->getUid(),
            'domain' => $serializer->normalize(
                $model->getDomain(),
                [AbstractNormalizer::GROUPS => ['main']]
            ),
            'user' => $serializer->normalize(
                $model->getUser(),
                [AbstractNormalizer::GROUPS => ['main']]
            )
        ];

        self::assertEquals($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupIgnoreDomain(): void
    {
        $serializer = Normalizer::getInstance();

        $model = ModelFactory::getTestableDomainUserRelation();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => ['main'],
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['domain']
            ]
        );

        $expected = [
            'uid' => $model->getUid(),
            'user' => $serializer->normalize(
                $model->getUser(),
                [AbstractNormalizer::GROUPS => ['main']]
            )
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupMainIgnoreUser(): void
    {
        $serializer = Normalizer::getInstance();

        $model = ModelFactory::getTestableDomainUserRelation();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => ['main'],
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['user']
            ]
        );

        $expected = [
            'uid' => $model->getUid(),
            'domain' => $serializer->normalize(
                $model->getDomain(),
                [AbstractNormalizer::GROUPS => ['main']]
            )
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
