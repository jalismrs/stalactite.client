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
    public const API_URL_PREFIX = ParentClient::API_URL_PREFIX . '/auth-token';
    
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\Domain\Client
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
     * clientUser
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\AuthToken\User\Client
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
