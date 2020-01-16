<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement\AuthToken;

use Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Client;
use Jalismrs\Stalactite\Client\AccessManagement\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Test\ClientTestTrait;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement\AuthToken
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testClientCustomer
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientCustomer() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientCustomer();
        $client2 = $baseClient->getClientCustomer();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testClientDomain
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientDomain() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientDomain();
        $client2 = $baseClient->getClientDomain();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testClientUser
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientUser() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientUser();
        $client2 = $baseClient->getClientUser();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
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
        $token    = JwtFactory::generateJwt($apiToken, 'client.test');
        
        $validation = new ValidationData();
        $validation->setAudience(JwtFactory::JWT_AUDIENCE);
        
        self::assertFalse($token->isExpired());
        self::assertTrue($token->hasClaim('challenge'));
        self::assertTrue($token->validate($validation));
        
        $challenge = $token->getClaim('challenge');
        $signer    = new Sha256();
        
        self::assertTrue($token->verify($signer, $challenge . $apiToken));
    }
}
