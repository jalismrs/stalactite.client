<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

use DateTimeImmutable;
use Jalismrs\Stalactite\Client\Authentication\Model\ServerApp;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token\Plain;

/**
 * Class TpaJwtFactory
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
final class TpaJwtFactory
{
    private const JWT_DURATION = 60;

    /**
     * @param ServerApp $serverApp
     * @param int $duration
     * @return Plain
     */
    public static function forge(ServerApp $serverApp, int $duration = self::JWT_DURATION): Plain
    {
        $time = time();

        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            Key\InMemory::plainText($serverApp->getTokenSignatureKey())
        );

        return $config->builder()
            ->issuedBy($serverApp->getName())
            ->issuedAt(DateTimeImmutable::createFromFormat('U', (string)$time))
            ->expiresAt(DateTimeImmutable::createFromFormat('U', (string)($time + $duration)))
            ->getToken($config->signer(), $config->signingKey());
    }
}
