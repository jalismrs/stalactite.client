<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * UserTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Data\Model
 */
class UserTest extends
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

        $model = ModelFactory::getTestableUser();

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

        $model = ModelFactory::getTestableUser();

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
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
            'email' => $model->getEmail(),
            'googleId' => $model->getGoogleId(),
            'admin' => $model->isAdmin()
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * testGroupMin
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
    public function testGroupMin(): void
    {
        $serializer = new Serializer();

        $model = ModelFactory::getTestableUser();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'min',
                ],
            ]
        );

        $expected = [
            'uid' => $model->getUid(),
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
            'email' => $model->getEmail(),
            'googleId' => $model->getGoogleId(),
            'admin' => $model->isAdmin(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * testGroupCreate
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
    public function testGroupCreate(): void
    {
        $serializer = new Serializer();

        $model = ModelFactory::getTestableUser();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'create',
                ],
            ]
        );

        $expected = [
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
            'email' => $model->getEmail(),
            'admin' => $model->isAdmin(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * testGroupUpdate
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
    public function testGroupUpdate(): void
    {
        $serializer = new Serializer();

        $model = ModelFactory::getTestableUser();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'update',
                ],
            ]
        );

        $expected = [
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
            'email' => $model->getEmail(),
            'admin' => $model->isAdmin(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * testGroupUpdateMe
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
    public function testGroupUpdateMe(): void
    {
        $serializer = new Serializer();

        $model = ModelFactory::getTestableUser();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'updateMe',
                ],
            ]
        );

        $expected = [
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
