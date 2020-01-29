<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken;

use Jalismrs\Stalactite\Client\AbstractClient;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Access\AuthToken
 */
class Client extends
    AbstractClient
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
     * @return Customer\Client
     */
    public function customers(): Customer\Client
    {
        if (null === $this->clientCustomer) {
            $this->clientCustomer = new Customer\Client($this->getHost());
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
     * @return Domain\Client
     */
    public function domains(): Domain\Client
    {
        if (null === $this->clientDomain) {
            $this->clientDomain = new Domain\Client($this->getHost());
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
     * @return User\Client
     */
    public function users(): User\Client
    {
        if (null === $this->clientUser) {
            $this->clientUser = new User\Client($this->getHost());
            $this->clientUser
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }

        return $this->clientUser;
    }
}
