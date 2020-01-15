<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\AuthToken;

use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\AccessManagement\Client as ParentClient;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\AuthToken
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PREFIX = ParentClient::API_URL_PREFIX . '/auth-token';
    
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
     * domain
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Domain\Client
     */
    public function domain() : Domain\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Domain\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * user
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\User\Client
     */
    public function user() : User\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new User\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * customer
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Customer\Client
     */
    public function customer() : Customer\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Customer\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
}
