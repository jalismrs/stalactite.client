<?php

namespace Jalismrs\Stalactite\Client\Util;

use Lcobucci\JWT\Signer\Hmac;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Token;

/**
 * Class TokenIdentifier
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
final class TokenIdentifier
{
    /**
     * isAppToken
     *
     * @static
     *
     * @param \Lcobucci\JWT\Token $token
     *
     * @return bool
     */
    public static function isAppToken(Token $token): bool
    {
        $signer = new Rsa\Sha256();
        return $token->getHeader('alg', '') === $signer->getAlgorithmId()
            && $token->hasClaim('sub')
            && $token->hasClaim('type');
    }
    
    /**
     * isTpaToken
     *
     * @static
     *
     * @param \Lcobucci\JWT\Token $token
     *
     * @return bool
     */
    public static function isTpaToken(Token $token): bool
    {
        $signer = new Hmac\Sha256();
        return $token->getHeader('alg', '') === $signer->getAlgorithmId()
            && $token->hasClaim('iss');
    }
}
