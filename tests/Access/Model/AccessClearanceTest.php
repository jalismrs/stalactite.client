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
 * TrustedAppTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access
 */
class AccessClearanceTest extends
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

        $model = ModelFactory::getTestableAccessClearance();

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

        $model = ModelFactory::getTestableAccessClearance();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'main',
                ],
            ]
        );

        $expected = [
            'accessGranted' => $model->hasAccessGranted(),
            'accessType' => $model->getAccessType(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
