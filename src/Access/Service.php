<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access;

use Jalismrs\Stalactite\Client\AbstractService;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access
 */
class Service extends
    AbstractService
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
     * @return AuthToken\Service
     */
    public function authToken() : AuthToken\Service
    {
        if (null === $this->clientAuthToken) {
            $this->clientAuthToken = new AuthToken\Service($this->getHost());
            $this->clientAuthToken
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }
        
        return $this->clientAuthToken;
    }
    
    /**
     * customer
     *
     * @return Customer\Service
     */
    public function customers() : Customer\Service
    {
        if (null === $this->clientCustomer) {
            $this->clientCustomer = new Customer\Service($this->getHost());
            $this->clientCustomer
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }
        
        return $this->clientCustomer;
    }
    
    /**
     * domain
     *
     * @return Domain\Service
     */
    public function domains() : Domain\Service
    {
        if (null === $this->clientDomain) {
            $this->clientDomain = new Domain\Service($this->getHost());
            $this->clientDomain
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }
        
        return $this->clientDomain;
    }
    
    /**
     * relation
     *
     * @return Relation\Service
     */
    public function relations() : Relation\Service
    {
        if (null === $this->clientRelation) {
            $this->clientRelation = new Relation\Service($this->getHost());
            $this->clientRelation
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }
        
        return $this->clientRelation;
    }
    
    /**
     * user
     *
     * @return User\Service
     */
    public function users() : User\Service
    {
        if (null === $this->clientUser) {
            $this->clientUser = new User\Service($this->getHost());
            $this->clientUser
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }
        
        return $this->clientUser;
    }
}
