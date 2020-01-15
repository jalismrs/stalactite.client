<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement;

use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\AccessManagement\AuthToken\AuthTokenClient;
use Jalismrs\Stalactite\Client\AccessManagement\Customer\CustomerClient;
use Jalismrs\Stalactite\Client\AccessManagement\User\UserClient;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement
 */
class Client extends
    AbstractClient
{
    public const API_URL_PREFIX = '/access';
    
    /**
     * domains
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\DomainClient
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\User\UserClient
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Customer\CustomerClient
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
    
    /**
     * relations
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\RelationClient
     */
    public function relations() : RelationClient
    {
        static $client = null;
        
        if (null === $client) {
            $client = new RelationClient(
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\AuthTokenClient
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
