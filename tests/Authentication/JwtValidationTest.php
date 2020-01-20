<?php
declare(strict_types = 1);

namespace Test\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * JwtValidationTest
 *
 * @package Test\Authentication
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
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testTransportExceptionThrownOnRSAPublicKeyFetching() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::CLIENT_TRANSPORT_ERROR);
        
        $client = new Client('invalidHost');
        
        $client->getRSAPublicKey();
    }
    
    /**
     * testValidToken
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \OutOfBoundsException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
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
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \OutOfBoundsException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvalidPublicKeyToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_STALACTITE_RSA_PUBLIC_KEY_ERROR);
        
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
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \OutOfBoundsException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvalidToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_STRING_ERROR);
        
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
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \OutOfBoundsException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testWrongIssuerToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_ISSUER_ERROR);
        
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
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \OutOfBoundsException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testExpiredToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::EXPIRED_JWT_ERROR);
        
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
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \OutOfBoundsException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvalidUserTypeToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_USER_TYPE_ERROR);
        
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
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \OutOfBoundsException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvalidJwtStructureMissingClaimToken() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_JWT_STRUCTURE_ERROR);
        
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
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \OutOfBoundsException
     */
    private function checkToken(
        string $token,
        string $publicKey
    ) : void {
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse($publicKey)
                ]
            )
        );
        
        $response = $mockAPIClient->validate($token);
        
        self::assertTrue($response);
    }
}
