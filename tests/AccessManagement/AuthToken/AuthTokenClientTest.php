<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\Test\AccessManagement\AuthToken;

use jalismrs\Stalactite\Client\AccessManagement\AuthToken\AuthTokenClient;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use PHPUnit\Framework\TestCase;

class AuthTokenClientTest extends TestCase
{
    public function testGenerateAuthTokenJwt(): void
    {
        $apiToken = 'fake api token';
        $token = AuthTokenClient::generateJwt($apiToken, 'client.test');

        $validation = new ValidationData();
        $validation->setAudience(AuthTokenClient::JWT_AUDIENCE);

        $this->assertFalse($token->isExpired());
        $this->assertTrue($token->hasClaim('challenge'));
        $this->assertTrue($token->validate($validation));

        $challenge = $token->getClaim('challenge');
        $signer = new Sha256();

        $this->assertTrue($token->verify($signer, $challenge . $apiToken));
    }
}
