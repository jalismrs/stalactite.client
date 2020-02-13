<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Model\Access;

use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * AccessClearanceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Model\Access
 */
class AccessClearanceTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws SerializerException
     */
    public function testGroupCommon() : void
    {
        $model = ModelFactory::getTestableAccessClearance();
        
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
        $model = ModelFactory::getTestableAccessClearance();
        
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
            'granted' => $model->isGranted(),
            'type'    => $model->getType(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
