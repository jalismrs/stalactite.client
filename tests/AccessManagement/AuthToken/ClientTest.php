<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement\AuthToken;

use Jalismrs\Stalactite\Client\AccessManagement\AuthToken\AuthTokenClient;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use PHPUnit\Framework\TestCase;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement\AuthToken
 */
class ClientTest extends
    TestCase
{
    /**
     * testGenerateAuthTokenJwt
     *
     * @return void
     *
     * @throws \BadMethodCallException
     * @throws \OutOfBoundsException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGenerateAuthTokenJwt() : void
    {
        $apiToken = 'fake api token';
        $token    = AuthTokenClient::generateJwt($apiToken, 'client.test');
        
        $validation = new ValidationData();
        $validation->setAudience(AuthTokenClient::JWT_AUDIENCE);
        
        self::assertFalse($token->isExpired());
        self::assertTrue($token->hasClaim('challenge'));
        self::assertTrue($token->validate($validation));
        
        $challenge = $token->getClaim('challenge');
        $signer    = new Sha256();
        
        self::assertTrue($token->verify($signer, $challenge . $apiToken));
    }
}
