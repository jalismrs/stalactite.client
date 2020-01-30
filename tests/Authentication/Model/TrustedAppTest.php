<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Model;

use Jalismrs\Stalactite\Client\Tests\Authentication\ModelFactory;
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
class TrustedAppTest extends
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

        $model = ModelFactory::getTestableTrustedApp();

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

        $model = ModelFactory::getTestableTrustedApp();

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
            'name' => $model->getName(),
            'authToken' => $model->getAuthToken(),
            'googleOAuthClientId' => $model->getGoogleOAuthClientId(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
    
    /**
     * testGroupReset
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
    public function testGroupReset(): void
    {
        $serializer = new Serializer();

        $model = ModelFactory::getTestableTrustedApp();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'reset',
                ],
            ]
        );

        $expected = [
            'resetToken' => $model->getResetToken(),
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

        $model = ModelFactory::getTestableTrustedApp();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'create',
                ],
            ]
        );

        $expected = [
            'googleOAuthClientId' => $model->getGoogleOAuthClientId(),
            'name' => $model->getName(),
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

        $model = ModelFactory::getTestableTrustedApp();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'update',
                ],
            ]
        );

        $expected = [
            'googleOAuthClientId' => $model->getGoogleOAuthClientId(),
            'name' => $model->getName(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
