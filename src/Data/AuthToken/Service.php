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
    /**
     * @var Customer\Service|null
     */
    private ?Customer\Service $serviceCustomer = null;
    /**
     * @var Domain\Service|null
     */
    private ?Domain\Service $serviceDomain = null;
    /**
     * @var Post\Service|null
     */
    private ?Post\Service $servicePost = null;
    /**
     * @var User\Service|null
     */
    private ?User\Service $serviceUser = null;
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * customers
     *
     * @return Customer\Service
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
     */
    public function users(): User\Service
    {
        if ($this->serviceUser === null) {
            $this->serviceUser = new User\Service($this->getClient());
        }

        return $this->serviceUser;
    }
}
