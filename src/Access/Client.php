<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access;

use Jalismrs\Stalactite\Client\AbstractClient;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Access
 */
class Client extends
    AbstractClient
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
     * @return AuthToken\Client
     */
    public function authToken(): AuthToken\Client
    {
        if (null === $this->clientAuthToken) {
            $this->clientAuthToken = new AuthToken\Client(
                $this->host,
                $this->getUserAgent(),
                $this->getHttpClient()
            );
        }

        return $this->clientAuthToken;
    }

    /**
     * customer
     *
     * @return Customer\Client
     */
    public function customers(): Customer\Client
    {
        if (null === $this->clientCustomer) {
            $this->clientCustomer = new Customer\Client(
                $this->host,
                $this->getUserAgent(),
                $this->getHttpClient()
            );
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
            $this->clientDomain = new Domain\Client(
                $this->host,
                $this->getUserAgent(),
                $this->getHttpClient()
            );
        }

        return $this->clientDomain;
    }

    /**
     * relation
     *
     * @return Relation\Client
     */
    public function relations(): Relation\Client
    {
        if (null === $this->clientRelation) {
            $this->clientRelation = new Relation\Client(
                $this->host,
                $this->getUserAgent(),
                $this->getHttpClient()
            );
        }

        return $this->clientRelation;
    }

    /**
     * user
     *
     * @return User\Client
     */
    public function users(): User\Client
    {
        if (null === $this->clientUser) {
            $this->clientUser = new User\Client(
                $this->host,
                $this->getUserAgent(),
                $this->getHttpClient()
            );
        }

        return $this->clientUser;
    }
}
