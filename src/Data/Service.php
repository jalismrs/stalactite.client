<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data;

use Jalismrs\Stalactite\Client\AbstractService;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data
 */
class Service extends
    AbstractService
{
    private $serviceAuthToken;
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
     * authToken
     *
     * @return AuthToken\Service
     */
    public function authToken(): AuthToken\Service
    {
        if (null === $this->serviceAuthToken) {
            $this->serviceAuthToken = new AuthToken\Service($this->getHost());
            $this->serviceAuthToken
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }

        return $this->serviceAuthToken;
    }

    /**
     * customer
     *
     * @return Customer\Service
     */
    public function customers(): Customer\Service
    {
        if (null === $this->serviceCustomer) {
            $this->serviceCustomer = new Customer\Service($this->getHost());
            $this->serviceCustomer
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
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
            $this->serviceDomain = new Domain\Service($this->getHost());
            $this->serviceDomain
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
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
            $this->servicePost = new Post\Service($this->getHost());
            $this->servicePost
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
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
            $this->serviceUser = new User\Service($this->getHost());
            $this->serviceUser
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }

        return $this->serviceUser;
    }
}
