<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Util;

use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

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
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $serializer1 = Serializer::getInstance();
        $serializer2 = Serializer::getInstance();

        self::assertSame($serializer1, $serializer2);
    }
}
