<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

use Jalismrs\Stalactite\Client\Authentication\Model\ServerApp;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

class TpaJwtFactory
{
    private const JWT_DURATION = 60;

    /**
     * @param ServerApp $serverApp
     * @param int $duration
     * @return Token
     */
    public static function forge(ServerApp $serverApp, int $duration = self::JWT_DURATION): Token
    {
        $time = time();

        $builder = (new Builder())
            ->issuedBy($serverApp->getName())
            ->issuedAt($time)
            ->expiresAt($time + $duration);

        $signer = new Sha256();
        return $builder->getToken($signer, new Key($serverApp->getTokenSignatureKey()));
    }
}
