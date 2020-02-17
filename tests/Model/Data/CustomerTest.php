<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Model\Data;

use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * CustomerTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Model\Data
 */
class CustomerTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupCommon(): void
    {
        $model = ModelFactory::getTestableCustomer();

        $actual = Serializer::getInstance()
            ->normalize($model);

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
        $model = ModelFactory::getTestableCustomer();

        $actual = Serializer::getInstance()
            ->normalize(
                $model,
                [
                    AbstractNormalizer::GROUPS => [
                        'main',
                    ],
                ]
            );

        $expected = [
            'uid' => $model->getUid(),
            'googleId' => $model->getGoogleId(),
            'email' => $model->getEmail(),
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
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
        $model = ModelFactory::getTestableCustomer();

        $actual = Serializer::getInstance()
            ->normalize(
                $model,
                [
                    AbstractNormalizer::GROUPS => [
                        'create',
                    ],
                ]
            );

        $expected = [
            'email' => $model->getEmail(),
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
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
        $model = ModelFactory::getTestableCustomer();

        $actual = Serializer::getInstance()
            ->normalize(
                $model,
                [
                    AbstractNormalizer::GROUPS => [
                        'update',
                    ],
                ]
            );

        $expected = [
            'email' => $model->getEmail(),
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
