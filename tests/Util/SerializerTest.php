<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Util;

use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\MappingException;

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
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     */
    public function testCreate(): void
    {
        $serializer1 = Serializer::getInstance();
        $serializer2 = Serializer::getInstance();

        self::assertSame($serializer1, $serializer2);
    }
}
