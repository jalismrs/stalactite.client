<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Access;

use Jalismrs\Stalactite\Client\Util\SerializerFactory;
use PHPUnit\Framework\TestCase;

/**
 * AccessClearanceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access
 */
class AccessClearanceTest extends
    TestCase
{
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
        $serializer = SerializerFactory::create();
        
        $object = ModelFactory::getTestableAccessClearance();
        
        $given = $serializer->normalize(
            $object,
            null,
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
        
        self::assertSame($expected, $given);
    }
}
