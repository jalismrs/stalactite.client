<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * DomainTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Data\Model
 */
class DomainTest extends
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
        $serializer = new Serializer();

        $model = ModelFactory::getTestableDomain();

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
        $serializer = new Serializer();

        $model = ModelFactory::getTestableDomain();

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
            'type' => $model->getType(),
            'apiKey' => $model->getApiKey(),
            'externalAuth' => $model->hasExternalAuth(),
            'generationDate' => $model->getGenerationDate(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * testGroupCreate
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGroupCreate(): void
    {
        $serializer = new Serializer();

        $model = ModelFactory::getTestableDomain();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'create',
                ],
            ]
        );

        $expected = [
            'apiKey' => $model->getApiKey(),
            'externalAuth' => $model->hasExternalAuth(),
            'generationDate' => $model->getGenerationDate(),
            'name' => $model->getName(),
            'type' => $model->getType(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * testGroupUpdate
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGroupUpdate(): void
    {
        $serializer = new Serializer();

        $model = ModelFactory::getTestableDomain();

        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'update',
                ],
            ]
        );

        $expected = [
            'apiKey' => $model->getApiKey(),
            'externalAuth' => $model->hasExternalAuth(),
            'generationDate' => $model->getGenerationDate(),
            'name' => $model->getName(),
            'type' => $model->getType(),
        ];

        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
