<?php

namespace Jalismrs\Stalactite\Client\Tests\Util;

use Jalismrs\Stalactite\Client\Util\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class ResponseTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Util
 *
 * @covers \Jalismrs\Stalactite\Client\Util\Response
 */
class ResponseTest extends
    TestCase
{
    /**
     * @param Response $response
     * @param bool     $expected
     *
     * @dataProvider getResponses
     */
    public function testIsSuccessful(
        Response $response,
        bool $expected
    ) : void {
        self::assertEquals(
            $expected,
            $response->isSuccessful()
        );
    }
    
    public function getResponses() : array
    {
        return [
            [
                new Response(100),
                false,
            ],
            [
                new Response(200),
                true,
            ],
            [
                new Response(300),
                false,
            ],
            [
                new Response(400),
                false,
            ],
            [
                new Response(500),
                false,
            ],
        ];
    }
}
