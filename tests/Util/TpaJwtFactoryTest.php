<?php

namespace Jalismrs\Stalactite\Client\Tests\Util;

use Jalismrs\Stalactite\Client\Tests\Authentication\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Util\TpaJwtFactory;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use PHPUnit\Framework\TestCase;

/**
 * Class TpaJwtFactoryTest
 * @package Jalismrs\Stalactite\Client\Tests\Util
 * @covers \Jalismrs\Stalactite\Client\Util\TpaJwtFactory
 */
class TpaJwtFactoryTest extends TestCase
{
    /**
     * assert that tpa token :
     * - contains the server app name as issuer
     * - is signed using the server app token signature key
     */
    public function testTpaJwtForge(): void
    {
        $serverApp = TestableModelFactory::getTestableServerApp();
        $token = TpaJwtFactory::forge($serverApp);

        self::assertSame(
            $serverApp->getName(),
            $token->claims()->get('iss')
        );

        $signer = new Sha256();
        $key = Key\InMemory::plainText($serverApp->getTokenSignatureKey());
        $config = Configuration::forSymmetricSigner(
            $signer,
            $key
        );

        self::assertTrue(
            $config->validator()->validate($token, ...[
                new SignedWith($signer, $key)
            ])
        );
    }
}
