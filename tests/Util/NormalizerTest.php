<?php

namespace Jalismrs\Stalactite\Client\Tests\Util;

use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\TestCase;

/**
 * Class NormalizerTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Util
 */
class NormalizerTest extends
    TestCase
{
    public function testSingleton() : void
    {
        self::assertSame(
            Normalizer::getInstance(),
            Normalizer::getInstance()
        );
    }
}
