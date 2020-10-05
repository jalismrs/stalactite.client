<?php

namespace Jalismrs\Stalactite\Client\Util;

use Lcobucci\JWT\Signer\Hmac;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Token;

final class TokenIdentifier
{
    public static function isAppToken(Token $token): bool
    {
        $signer = new Rsa\Sha256();
        return $token->getHeader('alg', '') === $signer->getAlgorithmId()
            && $token->hasClaim('sub')
            && $token->hasClaim('type');
    }

    public static function isTpaToken(Token $token): bool
    {
        $signer = new Hmac\Sha256();
        return $token->getHeader('alg', '') === $signer->getAlgorithmId()
            && $token->hasClaim('iss');
    }
}