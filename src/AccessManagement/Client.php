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
    public const API_URL_PART = '/access';
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * clientAuthToken
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Client
     */
    public function clientAuthToken() : AuthToken\Client
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
    
    /**
     * clientCustomer
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Customer\Client
     */
    public function clientCustomer() : Customer\Client
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
     * clientDomain
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Domain\Client
     */
    public function clientDomain() : Domain\Client
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
     * clientRelation
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Relation\Client
     */
    public function clientRelation() : Relation\Client
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
     * clientUser
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\User\Client
     */
    public function clientUser() : User\Client
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
}
