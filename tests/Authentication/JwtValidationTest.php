<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\JwtException;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use PHPUnit\Framework\TestCase;

/**
 * JwtValidationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication
 */
class JwtValidationTest extends TestCase
{
    private const TEST_RSA_PRIVATE_KEY = __DIR__ . '/keys/private.pem';
    private const TEST_RSA_PUBLIC_KEY = __DIR__ . '/keys/public.pem';

    /**
     * @throws ClientException
     * @throws JwtException
     */
    public function testValidToken(): void
    {
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (new Builder())
                ->issuedBy(Service::JWT_ISSUER)
                ->permittedFor('testTrustedAppName')
                ->relatedTo('0123456789')
                ->issuedAt($time)
                ->expiresAt($time + (60 * 60))
                ->withClaim('type', 'user')
                ->getToken($signer, $privateKey),
            self::getTestPublicKey()
        );
    }

    /**
     * @param Token $token
     * @param string $publicKey
     * @throws ClientException
     * @throws JwtException
     */
    private function checkToken(Token $token, string $publicKey): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create($publicKey)
        );

        self::assertTrue($mockService->validateJwt($token));
    }

    /**
     * getTestPublicKey
     *
     * @static
     * @return string
     */
    private static function getTestPublicKey(): string
    {
        return file_get_contents(self::TEST_RSA_PUBLIC_KEY);
    }

    /**
     * @throws ClientException
     * @throws JwtException
     */
    public function testInvalidPublicKeyToken(): void
    {
        $this->expectException(JwtException::class);
        $this->expectExceptionCode(JwtException::INVALID_STALACTITE_RSA_PUBLIC_KEY);

        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (new Builder())
                ->issuedBy(Service::JWT_ISSUER)
                ->permittedFor('testTrustedAppName')
                ->relatedTo('0123456789')
                ->issuedAt($time)
                ->expiresAt($time + (60 * 60))
                ->withClaim('type', 'user')
                ->getToken($signer, $privateKey),
            'invalid public key'
        );
    }

    /**
     * @throws ClientException
     * @throws JwtException
     */
    public function testWrongIssuerToken(): void
    {
        $this->expectException(JwtException::class);
        $this->expectExceptionCode(JwtException::INVALID_JWT_ISSUER);

        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (new Builder())
                ->issuedBy('wrong issuer')
                ->permittedFor('testTrustedAppName')
                ->relatedTo('0123456789')
                ->issuedAt($time)
                ->expiresAt($time + (60 * 60))
                ->withClaim('type', 'user')
                ->getToken($signer, $privateKey),
            self::getTestPublicKey()
        );
    }

    /**
     * @throws ClientException
     * @throws JwtException
     */
    public function testExpiredToken(): void
    {
        $this->expectException(JwtException::class);
        $this->expectExceptionCode(JwtException::EXPIRED_JWT);

        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (new Builder())
                ->issuedBy(Service::JWT_ISSUER)
                ->permittedFor('testTrustedAppName')
                ->relatedTo('0123456789')
                ->issuedAt($time - 2000)
                ->expiresAt($time - 1000)
                ->withClaim('type', 'user')
                ->getToken($signer, $privateKey),
            self::getTestPublicKey()
        );
    }

    /**
     * @throws ClientException
     * @throws JwtException
     */
    public function testInvalidUserTypeToken(): void
    {
        $this->expectException(JwtException::class);
        $this->expectExceptionCode(JwtException::INVALID_JWT_USER_TYPE);

        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (new Builder())
                ->issuedBy(Service::JWT_ISSUER)
                ->permittedFor('testTrustedAppName')
                ->relatedTo('0123456789')
                ->issuedAt($time)
                ->expiresAt($time + (60 * 60))
                ->withClaim('type', 'invalid type')
                ->getToken($signer, $privateKey),
            self::getTestPublicKey()
        );
    }

    /**
     * @throws ClientException
     * @throws JwtException
     */
    public function testInvalidJwtStructureMissingClaimToken(): void
    {
        $this->expectException(JwtException::class);
        $this->expectExceptionCode(JwtException::INVALID_JWT_STRUCTURE);

        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (new Builder())
                ->issuedBy(Service::JWT_ISSUER)
                ->permittedFor('testTrustedAppName')
                ->relatedTo('0123456789')
                ->issuedAt($time)
                ->expiresAt($time + (60 * 60))
                ->getToken($signer, $privateKey),
            self::getTestPublicKey()
        );
    }
}
