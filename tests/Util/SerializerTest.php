<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\TestCase;

/**
 * SerializerTest
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
class SerializerTest extends
    TestCase
{
    /**
     * testCreate
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public function testCreate() : void
    {
        $serializer1 = Serializer::create();
        $serializer2 = Serializer::create();
        
        self::assertSame($serializer1, $serializer2);
    }
}
