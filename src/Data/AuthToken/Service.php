<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken;

use Jalismrs\Stalactite\Client\AbstractService;

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
     * customer
     *
     * @return Customer\Service
     */
    public function customers(): Customer\Service
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
    public function domains(): Domain\Service
    {
        if (null === $this->serviceDomain) {
            $this->serviceDomain = new Domain\Service($this->getClient());
        }

        return $this->serviceDomain;
    }

    /**
     * post
     *
     * @return Post\Service
     */
    public function posts(): Post\Service
    {
        if (null === $this->servicePost) {
            $this->servicePost = new Post\Service($this->getClient());
        }

        return $this->servicePost;
    }

    /**
     * user
     *
     * @return User\Service
     */
    public function users(): User\Service
    {
        if (null === $this->serviceUser) {
            $this->serviceUser = new User\Service($this->getClient());
        }

        return $this->serviceUser;
    }
}
