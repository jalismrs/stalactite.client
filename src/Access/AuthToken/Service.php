<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken;

use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Exception\RequestException;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken
 */
class Service extends
    AbstractService
{
    private $serviceCustomer;
    private $serviceDomain;
    private $serviceUser;
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * customers
     *
     * @return Customer\Service
     *
     * @throws RequestException
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
     *
     * @throws RequestException
     */
    public function domains() : Domain\Service
    {
        if ($this->serviceDomain === null) {
            $this->serviceDomain = new Domain\Service($this->getClient());
        }
        
        return $this->serviceDomain;
    }
    
    /**
     * users
     *
     * @return User\Service
     *
     * @throws RequestException
     */
    public function users() : User\Service
    {
        if ($this->serviceUser === null) {
            $this->serviceUser = new User\Service($this->getClient());
        }
        
        return $this->serviceUser;
    }
}
