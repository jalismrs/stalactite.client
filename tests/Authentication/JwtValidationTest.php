<?php

namespace jalismrs\Stalactite\Client\Test\Authentication;

use jalismrs\Stalactite\Client\Authentication\Client;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class JwtValidationTest extends TestCase
{
    private const TEST_RSA_PUBLIC_KEY = __DIR__ . '/keys/public.pem';
    private const TEST_RSA_PRIVATE_KEY = __DIR__ . '/keys/private.pem';

    /**
     * @return Token
     */
    private static function generateValidTestJwt(): Token
    {
        $signer = new Sha256();
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);

        $time = time();
        return (new Builder())
            ->issuedBy(Client::JWT_ISSUER)
            ->permittedFor('testTrustedAppName')
            ->relatedTo('0123456789')
            ->issuedAt($time)
            ->expiresAt($time + (60 * 60))
            ->withClaim('type', 'user')
            ->getToken($signer, $privateKey);
    }

    /**
     * @return Token
     * The returned token is invalid because he :
     * - is expired
     * - has a wrong issuer
     * - has a wrong user type
     */
    private static function generateInvalidTestJwt(): Token
    {
        $signer = new Sha256();
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);

        $time = time();
        return (new Builder())
            ->issuedBy('Invalid issuer')
            ->permittedFor('testTrustedAppName')
            ->relatedTo('0123456789')
            ->issuedAt($time)
            ->expiresAt($time - 10)
            ->withClaim('type', 'invalid user type')
            ->getToken($signer, $privateKey);
    }

    /**
     * @return string
     */
    private static function getTestPublicKey(): string
    {
        return file_get_contents(self::TEST_RSA_PUBLIC_KEY);
    }

    public function testTokenValidation(): void
    {
        // use a mock http client to mock the public key http call
        $mockHttpClient = new MockHttpClient([
            new MockResponse(self::getTestPublicKey())
        ]);

        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $validToken = self::generateValidTestJwt();
        $invalidToken = self::generateInvalidTestJwt();

        $this->assertTrue($mockAPIClient->validate($validToken));
        $this->assertFalse($mockAPIClient->validate($invalidToken));
    }
}