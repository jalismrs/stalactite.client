<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Model\Data;

use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * DomainTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Model\Data
 */
class DomainTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupCommon() : void
    {
        $model = ModelFactory::getTestableDomain();
        
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
    public function testGroupMain() : void
    {
        $serializer = Serializer::getInstance();
        
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
            'uid'            => $model->getUid(),
            'name'           => $model->getName(),
            'type'           => $model->getType(),
            'apiKey'         => $model->getApiKey(),
            'externalAuth'   => $model->hasExternalAuth(),
            'generationDate' => $model->getGenerationDate(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
    
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupCreate() : void
    {
        $serializer = Serializer::getInstance();
        
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
            'apiKey'         => $model->getApiKey(),
            'externalAuth'   => $model->hasExternalAuth(),
            'generationDate' => $model->getGenerationDate(),
            'name'           => $model->getName(),
            'type'           => $model->getType(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
    
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupUpdate() : void
    {
        $serializer = Serializer::getInstance();
        
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
            'apiKey'         => $model->getApiKey(),
            'externalAuth'   => $model->hasExternalAuth(),
            'generationDate' => $model->getGenerationDate(),
            'name'           => $model->getName(),
            'type'           => $model->getType(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
