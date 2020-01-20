<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken;

use Jalismrs\Stalactite\Client\ClientAbstract;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Data\AuthToken
 */
class Client extends
    ClientAbstract
{
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
     * customer
     *
     * @return \Jalismrs\Stalactite\Client\Data\AuthToken\Customer\Client
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
     * @return \Jalismrs\Stalactite\Client\Data\AuthToken\Domain\Client
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
     * post
     *
     * @return \Jalismrs\Stalactite\Client\Data\AuthToken\Post\Client
     */
    public function posts() : Post\Client
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
     * user
     *
     * @return \Jalismrs\Stalactite\Client\Data\AuthToken\User\Client
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
