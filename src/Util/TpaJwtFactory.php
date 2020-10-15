<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

use Jalismrs\Stalactite\Client\Authentication\Model\ServerApp;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

/**
 * Class TpaJwtFactory
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
final class TpaJwtFactory
{
    private const JWT_DURATION = 60;
    
    /**
     * forge
     *
     * @static
     *
     * @param \Jalismrs\Stalactite\Client\Authentication\Model\ServerApp $serverApp
     * @param int                                                        $duration
     *
     * @return \Lcobucci\JWT\Token
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
