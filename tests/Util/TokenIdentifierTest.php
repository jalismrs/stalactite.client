<?php

namespace Jalismrs\Stalactite\Client\Tests\Util;

use Jalismrs\Stalactite\Client\Util\TokenIdentifier;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Key\LocalFileReference;
use Lcobucci\JWT\Signer\None;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Token\Plain;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenIdentifierTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Util
 *
 * @covers \Jalismrs\Stalactite\Client\Util\TokenIdentifier
 */
class TokenIdentifierTest extends TestCase
{
    /**
     * @param Plain $token
     * @param bool $expected
     * @dataProvider appTokenProvider
     */
    public function testIsAppToken(Plain $token, bool $expected): void
    {
        self::assertSame($expected, TokenIdentifier::isAppToken($token));
    }

    public function appTokenProvider(): array
    {
        $signer = new Rsa\Sha256();
        $config = Configuration::forAsymmetricSigner(
            $signer,
            LocalFileReference::file(__DIR__ . '/keys/private.pem'),
            LocalFileReference::file(__DIR__ . '/keys/public.pem'),
        );

        return [
            [
                $config->builder()->relatedTo('test')
                    ->withClaim('type', 'test')
                    ->getToken($config->signer(), $config->signingKey()),
                true,
            ],
            [
                $config->builder()->withClaim('type', 'test')
                    ->getToken($config->signer(), $config->signingKey()),
                false
                // missing sub
            ],
            [
                $config->builder()->relatedTo('test')
                    ->getToken($config->signer(), $config->signingKey()),
                false
                // missing type
            ],
            [
                $config->builder()->relatedTo('test')
                    ->withClaim('type', 'test')
                    ->getToken(new None(), InMemory::empty()),
                false
                // missing alg
            ]
        ];
    }

    /**
     * @param Plain $token
     * @param bool $expected
     * @dataProvider tpaTokenProvider
     */
    public function testIsTpaToken(Plain $token, bool $expected): void
    {
        self::assertSame($expected, TokenIdentifier::isTpaToken($token));
    }

    public function tpaTokenProvider(): array
    {
        $config = Configuration::forSymmetricSigner(
            new Hmac\Sha256(),
            InMemory::plainText('test')
        );

        return [
            [
                $config->builder()
                    ->issuedBy('test')
                    ->getToken($config->signer(), $config->signingKey()),
                true,
            ],
            [
                $config->builder()
                    ->getToken($config->signer(), $config->signingKey()),
                false
                // missing iss
            ],
            [
                $config->builder()
                    ->issuedBy('test')
                    ->getToken(new None(), InMemory::empty()),
                false
                // missing alg
            ]
        ];
    }
}
