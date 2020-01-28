<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * DomainTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 */
class DomainTest extends
    TestCase
{
    /**
     * testGroupCommon
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public function testGroupCommon() : void
    {
        $serializer = Serializer::getInstance();
        
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
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
     * testGroupCreate
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
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
     * testGroupUpdate
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
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
