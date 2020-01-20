<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access;

use Jalismrs\Stalactite\Client\ClientAbstract;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Access
 */
class Client extends
    ClientAbstract
{
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
     * authToken
     *
     * @return \Jalismrs\Stalactite\Client\Access\AuthToken\Client
     */
    public function authTokens() : AuthToken\Client
    {
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
     * customer
     *
     * @return \Jalismrs\Stalactite\Client\Access\Customer\Client
     */
    public function customers() : Customer\Client
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
     * domain
     *
     * @return \Jalismrs\Stalactite\Client\Access\Domain\Client
     */
    public function domains() : Domain\Client
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
     * relation
     *
     * @return \Jalismrs\Stalactite\Client\Access\Relation\Client
     */
    public function relations() : Relation\Client
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
     * user
     *
     * @return \Jalismrs\Stalactite\Client\Access\User\Client
     */
    public function users() : User\Client
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
