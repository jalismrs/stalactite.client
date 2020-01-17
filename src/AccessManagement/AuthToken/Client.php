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
     * getClientCustomer
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Customer\Client
     *
     * @throws \InvalidArgumentException
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
     * getClientDomain
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Domain\Client
     *
     * @throws \InvalidArgumentException
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
     * getClientUser
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\User\Client
     *
     * @throws \InvalidArgumentException
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
