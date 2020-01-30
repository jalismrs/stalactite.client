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
 * CustomerTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Data\Model
 */
class CustomerTest extends
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

        $model = ModelFactory::getTestableCustomer();

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

        $model = ModelFactory::getTestableCustomer();

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
            'googleId' => $model->getGoogleId(),
            'email' => $model->getEmail(),
            'firstName' => $model->getFirstName(),
            'lastName' => $model->getLastName(),
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

        $model = ModelFactory::getTestableCustomer();

        $actual = $serializer->normalize(
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

        $model = ModelFactory::getTestableCustomer();

        $actual = $serializer->normalize(
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
