<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement;

use Jalismrs\Stalactite\Client\ClientAbstract;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement
 */
class Client extends
    ClientAbstract
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
     * @return \Jalismrs\Stalactite\Client\DataManagement\Domain\Client
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
     * post
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Post\Client
     */
    public function post() : Post\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Post\Client(
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
     * @return \Jalismrs\Stalactite\Client\DataManagement\CertificationType\Client
     */
    public function certificationType() : CertificationType\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new CertificationType\Client(
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
     * @return \Jalismrs\Stalactite\Client\DataManagement\PhoneType\Client
     */
    public function phoneType() : PhoneType\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new PhoneType\Client(
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
     * @return \Jalismrs\Stalactite\Client\DataManagement\AuthToken\Client
     */
    public function authToken() : AuthToken\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new AuthToken\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
}
