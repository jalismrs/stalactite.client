<?php

namespace Jalismrs\Stalactite\Client\Tests\Util;

use Jalismrs\Stalactite\Client\Util\TokenIdentifier;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Token;
use PHPUnit\Framework\TestCase;

class TokenIdentifierTest extends TestCase
{
    /**
     * @param Token $token
     * @param bool $expected
     * @dataProvider appTokenProvider
     */
    public function testIsAppToken(Token $token, bool $expected): void
    {
        self::assertSame($expected, TokenIdentifier::isAppToken($token));
    }

    public function appTokenProvider(): array
    {
        $signer = new Rsa\Sha256();

        return [
            [
                (new Builder())
                    ->relatedTo('test')
                    ->withClaim('type', 'test')
                    ->withHeader('alg', $signer->getAlgorithmId())
                    ->getToken(),
                true
            ],
            [
                (new Builder())
                    ->withClaim('type', 'test')
                    ->withHeader('alg', $signer->getAlgorithmId())
                    ->getToken(),
                false // missing sub
            ],
            [
                (new Builder())
                    ->relatedTo('test')
                    ->withHeader('alg', $signer->getAlgorithmId())
                    ->getToken(),
                false // missing type
            ],
            [
                (new Builder())
                    ->relatedTo('test')
                    ->withClaim('type', 'test')
                    ->getToken(),
                false // missing alg
            ],
        ];
    }

    /**
     * @param Token $token
     * @param bool $expected
     * @dataProvider tpaTokenProvider
     */
    public function testIsTpaToken(Token $token, bool $expected): void
    {
        self::assertSame($expected, TokenIdentifier::isTpaToken($token));
    }

    public function tpaTokenProvider(): array
    {
        $signer = new Hmac\Sha256();

        return [
            [
                (new Builder())
                    ->issuedBy('test')
                    ->withHeader('alg', $signer->getAlgorithmId())
                    ->getToken(),
                true
            ],
            [
                (new Builder())
                    ->withHeader('alg', $signer->getAlgorithmId())
                    ->getToken(),
                false // missing iss
            ],
            [
                (new Builder())
                    ->issuedBy('test')
                    ->getToken(),
                false // missing alg
            ],
        ];
    }
}