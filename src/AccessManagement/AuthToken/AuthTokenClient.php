<?php

namespace jalismrs\Stalactite\Client\AccessManagement\AuthToken;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\AccessManagement\Client;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

class AuthTokenClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/auth-token';

    private const JWT_DURATION = 60;

    public const JWT_AUDIENCE = 'access.microservice';

    /**
     * @param string $apiAuthToken
     * @param string|null $userAgent
     * @return Token
     */
    public static function generateJwt(string $apiAuthToken, ?string $userAgent): Token
    {
        $time = time();
        $challenge = sha1($time);
        $signer = new Sha256();

        $builder = (new Builder())
            ->permittedFor(self::JWT_AUDIENCE)
            ->issuedAt($time)
            ->expiresAt($time + self::JWT_DURATION)
            ->withClaim('challenge', $challenge);

        if ($userAgent) {
            $builder->issuedBy($userAgent);
        }

        return $builder->getToken($signer, new Key($challenge . $apiAuthToken));
    }
}