<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\AuthToken;

use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\AccessManagement\Client;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

/**
 * AuthTokenClient
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\AuthToken
 */
class AuthTokenClient extends
    AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/auth-token';
    
    private const JWT_DURATION = 60;
    
    public const JWT_AUDIENCE = 'access.microservice';
    
    /**
     * @param string      $apiAuthToken
     * @param string|null $userAgent
     *
     * @return Token
     */
    public static function generateJwt(
        string $apiAuthToken,
        ?string $userAgent
    ) : Token {
        $time      = time();
        $challenge = sha1((string)$time);
        $signer    = new Sha256();
        
        $builder = (new Builder())
            ->permittedFor(self::JWT_AUDIENCE)
            ->issuedAt($time)
            ->expiresAt($time + self::JWT_DURATION)
            ->withClaim('challenge', $challenge);
        
        if ($userAgent) {
            $builder->issuedBy($userAgent);
        }
        
        return $builder->getToken($signer, new Key($challenge . $apiAuthToken));
    }
    
    /**
     * domains
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\DomainClient
     */
    public function domains() : DomainClient
    {
        static $client = null;
        
        if (null === $client) {
            $client = new DomainClient(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * users
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\UserClient
     */
    public function users() : UserClient
    {
        static $client = null;
        
        if (null === $client) {
            $client = new UserClient(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * customers
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\CustomerClient
     */
    public function customers() : CustomerClient
    {
        static $client = null;
        
        if (null === $client) {
            $client = new CustomerClient(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
}
