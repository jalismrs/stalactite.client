<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement;

use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\DataManagement\AuthToken\AuthTokenClient;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement
 */
class Client extends
    AbstractClient
{
    public const API_URL_PREFIX = '/data';
    
    /**
     * user
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\Client
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
     * @return \Jalismrs\Stalactite\Client\DataManagement\Customer\Client
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
    
    /**
     * domain
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\DomainClient
     */
    public function domain() : DomainClient
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
     * post
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\PostClient
     */
    public function post() : PostClient
    {
        static $client = null;
        
        if (null === $client) {
            $client = new PostClient(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * certificationType
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\CertificationTypeClient
     */
    public function certificationType() : CertificationTypeClient
    {
        static $client = null;
        
        if (null === $client) {
            $client = new CertificationTypeClient(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * phoneType
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\PhoneTypeClient
     */
    public function phoneType() : PhoneTypeClient
    {
        static $client = null;
        
        if (null === $client) {
            $client = new PhoneTypeClient(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * authToken
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\AuthToken\AuthTokenClient
     */
    public function authToken() : AuthTokenClient
    {
        static $client = null;
        
        if (null === $client) {
            $client = new AuthTokenClient(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
}
