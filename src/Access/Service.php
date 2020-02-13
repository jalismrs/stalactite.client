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
        if ($this->serviceAuthToken === null) {
            $this->serviceAuthToken = new AuthToken\Service($this->getClient());
        }
        
        return $this->serviceAuthToken;
    }
    
    /**
     * customers
     *
     * @return Customer\Service
     */
    public function customers() : Customer\Service
    {
        if ($this->serviceCustomer === null) {
            $this->serviceCustomer = new Customer\Service($this->getClient());
        }
        
        return $this->serviceCustomer;
    }
    
    /**
     * domains
     *
     * @return Domain\Service
     */
    public function domains() : Domain\Service
    {
        if ($this->serviceDomain === null) {
            $this->serviceDomain = new Domain\Service($this->getClient());
        }
        
        return $this->serviceDomain;
    }
    
    /**
     * relations
     *
     * @return Relation\Service
     */
    public function relations() : Relation\Service
    {
        if ($this->serviceRelation === null) {
            $this->serviceRelation = new Relation\Service($this->getClient());
        }
        
        return $this->serviceRelation;
    }
    
    /**
     * users
     *
     * @return User\Service
     */
    public function users() : User\Service
    {
        if ($this->serviceUser === null) {
            $this->serviceUser = new User\Service($this->getClient());
        }
        
        return $this->serviceUser;
    }
}
