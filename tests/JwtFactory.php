<?php

namespace Jalismrs\Stalactite\Client\Tests;

use Lcobucci\JWT\Token;

/**
 * Class JwtFactory
 *
 * @package Jalismrs\Stalactite\Client\Tests\Factory
 */
class JwtFactory
{
    /**
     * create
     *
     * @static
     * @return \Lcobucci\JWT\Token
     */
    public static function create(): Token
    {
        return new Token();
    }
}
