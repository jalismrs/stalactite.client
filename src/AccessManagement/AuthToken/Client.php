<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\AuthToken;

use Jalismrs\Stalactite\Client\AccessManagement\Client as ParentClient;
use Jalismrs\Stalactite\Client\ClientAbstract;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\AuthToken
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/auth-token';
    
    private $clientCustomer;
    private $clientDomain;
    private $clientUser;
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * clientCustomer
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Customer\Client
     */
    public function getClientCustomer() : Customer\Client
    {
        static $client = null;
        
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Domain\Client
     */
    public function getClientDomain() : Domain\Client
    {
        static $client = null;
        
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
     * clientUser
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\User\Client
     */
    public function getClientUser() : User\Client
    {
        static $client = null;
        
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
