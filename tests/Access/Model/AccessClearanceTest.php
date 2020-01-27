<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Model;

use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\TestCase;

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
        $serializer = Serializer::create();
        
        $object = ModelFactory::getTestableAccessClearance();
        
        $actual = $serializer->normalize($object);
        
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
        $serializer = Serializer::create();
        
        $object = ModelFactory::getTestableAccessClearance();
        
        $actual = $serializer->normalize(
            $object,
            [
                'groups' => [
                    'main',
                ],
            ]
        );
        
        $expected = [
            'accessGranted' => $object->hasAccessGranted(),
            'accessType'    => $object->getAccessType(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
