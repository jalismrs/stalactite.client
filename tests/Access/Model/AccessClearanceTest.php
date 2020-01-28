<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Model;

use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\TestCase;
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
            'accessType'    => $model->getAccessType(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
