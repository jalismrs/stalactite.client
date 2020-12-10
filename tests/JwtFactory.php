<?php

namespace Jalismrs\Stalactite\Client\Tests;

use Lcobucci\JWT\Configuration;
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
     * @return Token
     */
    public static function create(): Token
    {
        $config = Configuration::forUnsecuredSigner();
        return $config->builder()->getToken($config->signer(), $config->signingKey());
    }
}
