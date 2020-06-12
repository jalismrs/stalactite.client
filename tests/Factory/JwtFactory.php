<?php

namespace Jalismrs\Stalactite\Client\Tests\Factory;

use Lcobucci\JWT\Token;

abstract class JwtFactory
{
    public static function create(): Token
    {
        return new Token();
    }
}