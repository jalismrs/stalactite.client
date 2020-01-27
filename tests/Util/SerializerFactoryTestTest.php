<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Util\SerializerFactory;
use PHPUnit\Framework\TestCase;

/**
 * SerializerFactoryTestTest
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
class SerializerFactoryTestTest extends
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
     */
    public function testCreate() : void
    {
        $serializer1 = SerializerFactory::create();
        $serializer2 = SerializerFactory::create();
        
        self::assertSame($serializer1, $serializer2);
    }
}
