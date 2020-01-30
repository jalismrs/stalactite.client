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
    private $serviceAuthToken;
    private $serviceCustomer;
    private $serviceDomain;
    private $serviceRelation;
    private $serviceUser;
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
        if (null === $this->serviceAuthToken) {
            $this->serviceAuthToken = new AuthToken\Service($this->getClient());
        }
        
        return $this->serviceAuthToken;
    }
    
    /**
     * customer
     *
     * @return Customer\Service
     */
    public function customers() : Customer\Service
    {
        if (null === $this->serviceCustomer) {
            $this->serviceCustomer = new Customer\Service($this->getClient());
        }
        
        return $this->serviceCustomer;
    }
    
    /**
     * domain
     *
     * @return Domain\Service
     */
    public function domains() : Domain\Service
    {
        if (null === $this->serviceDomain) {
            $this->serviceDomain = new Domain\Service($this->getClient());
        }
        
        return $this->serviceDomain;
    }
    
    /**
     * relation
     *
     * @return Relation\Service
     */
    public function relations() : Relation\Service
    {
        if (null === $this->serviceRelation) {
            $this->serviceRelation = new Relation\Service($this->getClient());
        }
        
        return $this->serviceRelation;
    }
    
    /**
     * user
     *
     * @return User\Service
     */
    public function users() : User\Service
    {
        if (null === $this->serviceUser) {
            $this->serviceUser = new User\Service($this->getClient());
        }
        
        return $this->serviceUser;
    }
}
