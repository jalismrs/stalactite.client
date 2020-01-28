<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
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
 * UserTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
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
            'admin' => $model->isAdmin(),
            'leads' => $serializer->normalize(
                $model->getLeads(),
                [
                    AbstractNormalizer::GROUPS => [
                        'main',
                    ],
                ]
            ),
            'posts' => $serializer->normalize(
                $model->getPosts(),
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
     * testGroupMin
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
    public function testGroupMin(): void
    {
        $serializer = Serializer::getInstance();

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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     */
    public function testGroupCreate(): void
    {
        $serializer = Serializer::getInstance();

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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     */
    public function testGroupUpdate(): void
    {
        $serializer = Serializer::getInstance();

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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     */
    public function testGroupUpdateMe(): void
    {
        $serializer = Serializer::getInstance();

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
