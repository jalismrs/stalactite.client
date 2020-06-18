<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

/**
 * Class TpaJwtFactory
 * @package Jalismrs\Stalactite\Client\Util
 */
class TpaJwtFactory
{
    public const DATA_API_JWT_AUDIENCE = 'data.microservice';
    public const ACCESS_API_JWT_AUDIENCE = 'access.microservice';

    private const JWT_DURATION = 60;

    public static function data(string $tokenSalt, ?string $userAgent = null): Token
    {
        return self::forge($tokenSalt, self::DATA_API_JWT_AUDIENCE, $userAgent);
    }

    /**
     * @param string $tokenSalt
     * @param string $audience
     * @param string|null $userAgent
     * @return Token
     */
    public static function forge(string $tokenSalt, string $audience, ?string $userAgent = null): Token
    {
        $time = time();
        $challenge = sha1((string)$time);

        $builder = (new Builder())
            ->permittedFor($audience)
            ->issuedAt($time)
            ->expiresAt($time + self::JWT_DURATION)
            ->withClaim('challenge', $challenge);

        if ($userAgent) {
            $builder->issuedBy($userAgent);
        }

        $signer = new Sha256();
        return $builder->getToken($signer, new Key($challenge . $tokenSalt));
    }

    public static function access(string $tokenSalt, ?string $userAgent = null): Token
    {
        return self::forge($tokenSalt, self::ACCESS_API_JWT_AUDIENCE, $userAgent);
    }
}
