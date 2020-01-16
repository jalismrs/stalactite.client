<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\AuthToken;

use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\DataManagement\Client as ParentClient;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\AuthToken
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/auth-token';
    
    private $clientCustomer;
    private $clientDomain;
    private $clientPost;
    private $clientUser;
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * clientCustomer
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\AuthToken\Customer\Client
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
     * @return \Jalismrs\Stalactite\Client\DataManagement\AuthToken\Domain\Client
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
     * clientPost
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\AuthToken\Post\Client
     */
    public function getClientPost() : Post\Client
    {
        if (null === $this->clientPost) {
            $this->clientPost = new Post\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $this->clientPost;
    }
    
    /**
     * clientUser
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\AuthToken\User\Client
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
