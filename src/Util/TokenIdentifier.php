<?php

namespace Jalismrs\Stalactite\Client\Util;

use Lcobucci\JWT\Signer\Hmac;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Token\Plain;

/**
 * Class TokenIdentifier
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
final class TokenIdentifier
{
    /**
     * @param Plain $token
     * @return bool
     */
    public static function isAppToken(Plain $token): bool
    {
//        var_dump($token->headers()->get('alg', ''));
//        var_dump($token->claims()->has('sub'));
//        var_dump($token->claims()->has('type'));
        return $token->headers()->get('alg', '') === (new Rsa\Sha256())->algorithmId()
            && $token->claims()->has('sub')
            && $token->claims()->has('type');
    }

    /**
     * @param Plain $token
     * @return bool
     */
    public static function isTpaToken(Plain $token): bool
    {
        return $token->headers()->get('alg', '') === (new Hmac\Sha256())->algorithmId()
            && $token->claims()->has('iss');
    }
}
