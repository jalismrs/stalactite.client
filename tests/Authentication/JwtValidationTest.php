<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\Test\Authentication;

use jalismrs\Stalactite\Client\Authentication\Client;
use jalismrs\Stalactite\Client\ClientException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class JwtValidationTest extends TestCase
{
    private const TEST_RSA_PUBLIC_KEY = __DIR__ . '/keys/public.pem';
    private const TEST_RSA_PRIVATE_KEY = __DIR__ . '/keys/private.pem';

    /**
     * @return string
     */
    private static function getTestPublicKey(): string
    {
        return file_get_contents(self::TEST_RSA_PUBLIC_KEY);
    }

    /**
     * @throws ClientException
     */
    public function testTransportExceptionThrownOnRSAPublicKeyFetching(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::CLIENT_TRANSPORT_ERROR);

        $client = new Client('invalidHost');
        $client->getRSAPublicKey();
    }

    /**
     * @dataProvider getTestableData
     * @param string $token
     * @param string $publicKey
     * @param bool $shoudlThrow
     * @param int $exceptionCode
     * @throws ClientException
     */
    public function testToken(string $token, string $publicKey, bool $shoudlThrow, int $exceptionCode): void
    {
        if ($shoudlThrow) {
            $this->expectException(ClientException::class);
            $this->expectExceptionCode($exceptionCode);
        }

        $mockHttpClient = new MockHttpClient([
            new MockResponse($publicKey)
        ]);

        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertTrue($mockAPIClient->validate($token));
    }

    /**
     * @return array
     */
    public function getTestableData(): array
    {
        $signer = new Sha256();
        $privateKey = new Key('file://' . self::TEST_RSA_PRIVATE_KEY);
        $publicKey = self::getTestPublicKey();

        $time = time();

        $validToken = (string)(new Builder())->issuedBy(Client::JWT_ISSUER)->permittedFor('testTrustedAppName')->relatedTo('0123456789')->issuedAt($time)->expiresAt($time + (60 * 60))->withClaim('type', 'user')->getToken($signer, $privateKey);
        $invalidJwt = 'a' . (new Builder())->issuedBy(Client::JWT_ISSUER)->permittedFor('testTrustedAppName')->relatedTo('0123456789')->issuedAt($time)->expiresAt($time + (60 * 60))->withClaim('type', 'user')->getToken($signer, $privateKey);
        $wrongIssuerToken = (string)(new Builder())->issuedBy('wrong issuer')->permittedFor('testTrustedAppName')->relatedTo('0123456789')->issuedAt($time)->expiresAt($time + (60 * 60))->withClaim('type', 'user')->getToken($signer, $privateKey);
        $expiredToken = (string)(new Builder())->issuedBy(Client::JWT_ISSUER)->permittedFor('testTrustedAppName')->relatedTo('0123456789')->issuedAt($time - 2000)->expiresAt($time - 1000)->withClaim('type', 'user')->getToken($signer, $privateKey);
        $invalidUserTypeToken = (string)(new Builder())->issuedBy(Client::JWT_ISSUER)->permittedFor('testTrustedAppName')->relatedTo('0123456789')->issuedAt($time)->expiresAt($time + (60 * 60))->withClaim('type', 'invalid type')->getToken($signer, $privateKey);
        $invalidStructureJwt_missingTypeClaim = (string)(new Builder())->issuedBy(Client::JWT_ISSUER)->permittedFor('testTrustedAppName')->relatedTo('0123456789')->issuedAt($time)->expiresAt($time + (60 * 60))->getToken($signer, $privateKey);

        return [
            [$validToken, $publicKey, false, 0],
            [$validToken, 'invalid public key', true, ClientException::INVALID_STALACTITE_RSA_PUBLIC_KEY_ERROR],
            [$invalidJwt, $publicKey, true, ClientException::INVALID_JWT_STRING_ERROR],
            [$wrongIssuerToken, $publicKey, true, ClientException::INVALID_JWT_ISSUER_ERROR],
            [$expiredToken, $publicKey, true, ClientException::EXPIRED_JWT_ERROR],
            [$invalidUserTypeToken, $publicKey, true, ClientException::INVALID_JWT_USER_TYPE_ERROR],
            [$invalidStructureJwt_missingTypeClaim, $publicKey, true, ClientException::INVALID_JWT_STRUCTURE_ERROR]
        ];
    }
}
