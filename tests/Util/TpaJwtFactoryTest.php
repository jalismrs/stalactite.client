<?php

namespace Jalismrs\Stalactite\Client\Tests\Util;

use Jalismrs\Stalactite\Client\Util\TpaJwtFactory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use PHPUnit\Framework\TestCase;

class TpaJwtFactoryTest extends TestCase
{
    private const DATA_API_SIGNATURE_SALT = 'dataApiSignatureSalt';

    public function testApiSpecificJwt(): void
    {
        self::assertSame(TpaJwtFactory::DATA_API_JWT_AUDIENCE, TpaJwtFactory::data(self::DATA_API_SIGNATURE_SALT, null)->getClaim('aud'));
    }

    /**
     * @param string $signatureSalt
     * @param string $audience
     * @param string|null $userAgent
     * @dataProvider provideJwtInfos
     */
    public function testTpaJwtForge(string $signatureSalt, string $audience, ?string $userAgent = null): void
    {
        $token = TpaJwtFactory::forge($signatureSalt, $audience, $userAgent);

        self::assertSame($audience, $token->getClaim('aud'));
        self::assertTrue($token->hasClaim('challenge'));

        if ($userAgent) {
            self::assertSame($userAgent, $token->getClaim('iss'));
        }

        $challenge = $token->getClaim('challenge');
        $signer = new Sha256();
        self::assertTrue($token->verify($signer, new Key($challenge . $signatureSalt)));
    }

    /**
     * @return array
     */
    public function provideJwtInfos(): array
    {
        return [
            ['random salt', 'me'], // no user agent
            ['random salt', 'me', 'secret agent (CIA maybe)']
        ];
    }
}