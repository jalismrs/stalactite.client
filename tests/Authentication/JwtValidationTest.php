<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use OutOfBoundsException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * JwtValidationTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Authentication
 */
class JwtValidationTest extends
    TestCase
{
    private const TEST_RSA_PRIVATE_KEY = __DIR__ . '/keys/private.pem';
    private const TEST_RSA_PUBLIC_KEY  = __DIR__ . '/keys/public.pem';
    
    /**
     * getTestPublicKey
     *
     * @static
     * @return string
     */
    private static function getTestPublicKey() : string
    {
        return file_get_contents(self::TEST_RSA_PUBLIC_KEY);
    }
    
    /**
     * testTransportExceptionThrownOnRSAPublicKeyFetching
     *
     * @return void
     *
     * @throws ClientException
     */
    public function testTransportExceptionThrownOnRSAPublicKeyFetching() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::CLIENT_TRANSPORT);
        
        $mockClient = new Client('invalidHost');
        
        $mockClient->getRSAPublicKey();
    }
    
    /**
     * testValidToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws OutOfBoundsException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testValidToken() : void
    {
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer     = new Sha256();
        $time       = time();
        
        $this->checkToken(
            (string)(new Builder())
                ->issuedBy(Client::JWT_ISSUER)
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
     * testInvalidPublicKeyToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws OutOfBoundsException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInvalidPublicKeyToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_STALACTITE_RSA_PUBLIC_KEY);
        
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer     = new Sha256();
        $time       = time();
        
        $this->checkToken(
            (string)(new Builder())
                ->issuedBy(Client::JWT_ISSUER)
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
     * @throws OutOfBoundsException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInvalidToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_STRING);
        
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer     = new Sha256();
        $time       = time();
        
        $this->checkToken(
            'a' . (new Builder())
                ->issuedBy(Client::JWT_ISSUER)
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
     * testWrongIssuerToken
     *
     * @return void
     *
     * @throws ClientException
     * @throws OutOfBoundsException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testWrongIssuerToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_ISSUER);
        
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer     = new Sha256();
        $time       = time();
        
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
     * @throws OutOfBoundsException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testExpiredToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::EXPIRED_JWT);
        
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer     = new Sha256();
        $time       = time();
        
        $this->checkToken(
            (string)(new Builder())
                ->issuedBy(Client::JWT_ISSUER)
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
     * @throws OutOfBoundsException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInvalidUserTypeToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_USER_TYPE);
        
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer     = new Sha256();
        $time       = time();
        
        $this->checkToken(
            (string)(new Builder())
                ->issuedBy(Client::JWT_ISSUER)
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
     * @throws OutOfBoundsException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInvalidJwtStructureMissingClaimToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_STRUCTURE);
        
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $signer     = new Sha256();
        $time       = time();
        
        $this->checkToken(
            (string)(new Builder())
                ->issuedBy(Client::JWT_ISSUER)
                ->permittedFor('testTrustedAppName')
                ->relatedTo('0123456789')
                ->issuedAt($time)
                ->expiresAt($time + (60 * 60))
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
     * @throws OutOfBoundsException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    private function checkToken(
        string $token,
        string $publicKey
    ) : void {
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse($publicKey)
                ]
            )
        );
        
        $response = $mockClient->validate($token);
        
        self::assertTrue($response);
    }
}
