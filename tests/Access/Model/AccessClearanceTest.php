<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Model;

use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * TrustedAppTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Access
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
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     * @throws SerializerException
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testGroupCommon(): void
    {
        $serializer = new Serializer();

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
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     * @throws SerializerException
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     */
    public function testGroupMain(): void
    {
        $serializer = new Serializer();

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
