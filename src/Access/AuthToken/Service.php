<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken;

use Jalismrs\Stalactite\Client\AbstractService;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken
 */
class Service extends
    AbstractService
{
    private $clientCustomer;
    private $clientDomain;
    private $clientUser;
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
    public function domains(): Domain\Service
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
     * user
     *
     * @return User\Service
     */
    public function users(): User\Service
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
