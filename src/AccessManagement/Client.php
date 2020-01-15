<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement;

use Jalismrs\Stalactite\Client\ClientAbstract;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PREFIX = '/access';
    
    /**
     * domain
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Domain\Client
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\User\Client
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Customer\Client
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
     * relation
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Relation\Client
     */
    public function relation() : Relation\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Relation\Client(
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Client
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
