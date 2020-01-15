<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement;

use Jalismrs\Stalactite\Client\DataManagement\AuthToken\AuthTokenClient;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use PHPUnit\Framework\TestCase;

/**
 * AuthTokenClientTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement
 */
class AuthTokenClientTest extends
    TestCase
{
    /**
     * testGenerateAuthTokenJwt
     *
     * Those are the ways the API checks the JWT
     * Since the API token here is invalid, the forged JWT will be invalid for the API but should still has a valid
     * format and signature
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
