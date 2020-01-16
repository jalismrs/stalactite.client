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
    
    private $clientAuthToken;
    private $clientCustomer;
    private $clientDomain;
    private $clientRelation;
    private $clientUser;
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
    public function getClientAuthToken() : AuthToken\Client
    {
        static $client = null;
        
        if (null === $this->clientAuthToken) {
            $this->clientAuthToken = new AuthToken\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $this->clientAuthToken;
    }
    
    /**
     * clientCustomer
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Customer\Client
     */
    public function getClientCustomer() : Customer\Client
    {
        if (null === $this->clientCustomer) {
            $this->clientCustomer = new Customer\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $this->clientCustomer;
    }
    
    /**
     * clientDomain
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Domain\Client
     */
    public function getClientDomain() : Domain\Client
    {
        if (null === $this->clientDomain) {
            $this->clientDomain = new Domain\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $this->clientDomain;
    }
    
    /**
     * clientRelation
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Relation\Client
     */
    public function getClientRelation() : Relation\Client
    {
        if (null === $this->clientRelation) {
            $this->clientRelation = new Relation\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $this->clientRelation;
    }
    
    /**
     * clientUser
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\User\Client
     */
    public function getClientUser() : User\Client
    {
        if (null === $this->clientUser) {
            $this->clientUser = new User\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $this->clientUser;
    }
}
