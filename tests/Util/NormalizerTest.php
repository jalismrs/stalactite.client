<?php

namespace Jalismrs\Stalactite\Client\Tests\Util;

use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\TestCase;
use function tmpfile;

/**
 * Class NormalizerTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Util
 *
 * @covers \Jalismrs\Stalactite\Client\Util\Normalizer
 */
class NormalizerTest extends
    TestCase
{
    /**
     * testSingleton
     *
     * @return void
     */
    public function testSingleton(): void
    {
        self::assertSame(
            Normalizer::getInstance(),
            Normalizer::getInstance()
        );
    }

    /**
     * testNormalize
     *
     * @return void
     *
     * @throws NormalizerException
     */
    public function testNormalize(): void
    {
        // arrange
        $systemUnderTest = Normalizer::getInstance();

        $input = tmpfile();

        // expect
        $this->expectException(NormalizerException::class);

        // act
        $systemUnderTest->normalize(
            $input
        );
    }
}
