<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use OutOfBoundsException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * JwtValidationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication
 */
class JwtValidationTest extends
    TestCase
{
    private const TEST_RSA_PRIVATE_KEY = __DIR__ . '/keys/private.pem';
    private const TEST_RSA_PUBLIC_KEY = __DIR__ . '/keys/public.pem';

    /**
     * testTransportExceptionThrownOnRSAPublicKeyFetching
     *
     * @return void
     *
     * @throws ClientException
     */
    public function testTransportExceptionThrownOnRSAPublicKeyFetching(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::CLIENT_TRANSPORT);

        $mockClient = new Client('invalidHost');
        $mockService = new Service($mockClient);

        $mockService->getRSAPublicKey();
    }

    /**
     * testValidToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testValidToken(): void
    {
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (string)(new Builder())
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
     * checkToken
     *
     * @param string $token
     * @param string $publicKey
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    private function checkToken(
        string $token,
        string $publicKey
    ): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create($publicKey)
        );

        $response = $mockService->validate($token);

        self::assertTrue($response);
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
     * testInvalidPublicKeyToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testInvalidPublicKeyToken(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_STALACTITE_RSA_PUBLIC_KEY);

        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (string)(new Builder())
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
     * testInvalidToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testInvalidToken(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_STRING);

        $this->checkToken(
            'invalid-token',
            self::getTestPublicKey()
        );
    }

    /**
     * testWrongIssuerToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testWrongIssuerToken(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_ISSUER);

        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (string)(new Builder())
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
     * testExpiredToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testExpiredToken(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::EXPIRED_JWT);

        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (string)(new Builder())
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
     * testInvalidUserTypeToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testInvalidUserTypeToken(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_USER_TYPE);

        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (string)(new Builder())
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
     * testInvalidJwtStructureMissingClaimToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testInvalidJwtStructureMissingClaimToken(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_STRUCTURE);

        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer = new Sha256();
        $time = time();

        $this->checkToken(
            (string)(new Builder())
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
