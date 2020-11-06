<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class DomainCustomerRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Model\DomainCustomerRelation
 */
class DomainCustomerRelationTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCommon(): void
    {
        $model = TestableModelFactory::getTestableDomainCustomerRelation();

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

        $model = TestableModelFactory::getTestableDomainCustomerRelation();

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
            'customer' => $serializer->normalize(
                $model->getCustomer(),
                [AbstractNormalizer::GROUPS => ['main']]
            ),
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
    public function testGroupIgnoreDomain(): void
    {
        $serializer = Normalizer::getInstance();

        $model = TestableModelFactory::getTestableDomainCustomerRelation();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => ['main'],
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['domain'],
            ]
        );

        $expected = [
            'uid' => $model->getUid(),
            'customer' => $serializer->normalize(
                $model->getCustomer(),
                [AbstractNormalizer::GROUPS => ['main']]
            ),
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
    public function testGroupMainIgnoreCustomer(): void
    {
        $serializer = Normalizer::getInstance();

        $model = TestableModelFactory::getTestableDomainCustomerRelation();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => ['main'],
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['customer'],
            ]
        );

        $expected = [
            'uid' => $model->getUid(),
            'domain' => $serializer->normalize(
                $model->getDomain(),
                [AbstractNormalizer::GROUPS => ['main']]
            ),
        ];

        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }
}
