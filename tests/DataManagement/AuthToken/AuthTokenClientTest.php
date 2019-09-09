<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement;

use jalismrs\Stalactite\Client\DataManagement\AuthToken\AuthTokenClient;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use PHPUnit\Framework\TestCase;

class AuthTokenClientTest extends TestCase
{
    /**
     * Those are the ways the API checks the JWT
     * Since the API token here is invalid, the forged JWT will be invalid for the API but should still has a valid format and signature
     */
    public function testGenerateAuthTokenJwt(): void
    {
        $apiToken = 'fake api token';
        $token = AuthTokenClient::generateJwt($apiToken, 'client.test');

        $validation = new ValidationData();
        $validation->setAudience('data.microservice');
        $validation->has('challenge');

        $this->assertFalse($token->isExpired());
        $this->assertTrue($token->validate($validation));

        $challenge = $token->getClaim('challenge');
        $signer = new Sha256();

        $this->assertTrue($token->verify($signer, $challenge . $apiToken));
    }
}