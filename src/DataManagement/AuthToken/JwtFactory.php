<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\AuthToken;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

/**
 * JwtFactory
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\AuthToken
 */
class JwtFactory
{
    public const JWT_AUDIENCE = 'data.microservice';
    
    private const JWT_DURATION = 60;
    
    /**
     * generateJwt
     *
     * @static
     *
     * @param string      $apiAuthToken
     * @param null|string $userAgent
     *
     * @return \Lcobucci\JWT\Token
     */
    public static function generateJwt(
        string $apiAuthToken,
        ?string $userAgent
    ) : Token {
        $time      = time();
        $challenge = sha1((string)$time);
        $signer    = new Sha256();
        
        $builder = (new Builder())
            ->permittedFor(self::JWT_AUDIENCE)
            ->issuedAt($time)
            ->expiresAt($time + self::JWT_DURATION)
            ->withClaim('challenge', $challenge);
        
        if (null !== $userAgent) {
            $builder->issuedBy($userAgent);
        }
        
        return $builder->getToken($signer, new Key($challenge . $apiAuthToken));
    }
}