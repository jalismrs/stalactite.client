<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken;

use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\AuthToken
 */
class Service extends
    AbstractService
{
    private $serviceCustomer;
    private $serviceDomain;
    private $servicePost;
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
     * @throws RequestConfigurationException
     */
    public function customers(): Customer\Service
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
     * @throws RequestConfigurationException
     */
    public function domains(): Domain\Service
    {
        if ($this->serviceDomain === null) {
            $this->serviceDomain = new Domain\Service($this->getClient());
        }

        return $this->serviceDomain;
    }
    
    /**
     * posts
     *
     * @return Post\Service
     *
     * @throws RequestConfigurationException
     */
    public function posts(): Post\Service
    {
        if ($this->servicePost === null) {
            $this->servicePost = new Post\Service($this->getClient());
        }

        return $this->servicePost;
    }
    
    /**
     * users
     *
     * @return User\Service
     *
     * @throws RequestConfigurationException
     */
    public function users(): User\Service
    {
        if ($this->serviceUser === null) {
            $this->serviceUser = new User\Service($this->getClient());
        }

        return $this->serviceUser;
    }
}
