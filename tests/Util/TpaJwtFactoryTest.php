<?php

namespace Jalismrs\Stalactite\Client\Tests\Util;

use Jalismrs\Stalactite\Client\Tests\Factory\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Util\TpaJwtFactory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use PHPUnit\Framework\TestCase;

class TpaJwtFactoryTest extends TestCase
{
    public function testTpaJwtForge(): void
    {
        $serverApp = ModelFactory::getTestableServerApp();
        $token = TpaJwtFactory::forge($serverApp);

        self::assertSame($serverApp->getName(), $token->getClaim('iss'));

        $signer = new Sha256();
        self::assertTrue($token->verify($signer, new Key($serverApp->getTokenSignatureKey())));
    }
}