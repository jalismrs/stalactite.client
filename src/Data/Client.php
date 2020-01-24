<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data;

use Jalismrs\Stalactite\Client\ClientAbstract;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Data
 */
class Client extends
    ClientAbstract
{
    private $clientAuthToken;
    private $clientCertificationType;
    private $clientCustomer;
    private $clientDomain;
    private $clientPhoneType;
    private $clientPost;
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
    public function authTokens(): AuthToken\Client
    {
        if (null === $this->clientAuthToken) {
            $this->clientAuthToken = new AuthToken\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientAuthToken;
    }

    /**
     * certificationType
     *
     * @return CertificationType\Client
     */
    public function certificationTypes(): CertificationType\Client
    {
        if (null === $this->clientCertificationType) {
            $this->clientCertificationType = new CertificationType\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientCertificationType;
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
                $this->userAgent,
                $this->httpClient
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
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientDomain;
    }

    /**
     * phoneType
     *
     * @return PhoneType\Client
     */
    public function phoneTypes(): PhoneType\Client
    {
        if (null === $this->clientPhoneType) {
            $this->clientPhoneType = new PhoneType\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientPhoneType;
    }

    /**
     * post
     *
     * @return Post\Client
     */
    public function posts(): Post\Client
    {
        if (null === $this->clientPost) {
            $this->clientPost = new Post\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientPost;
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
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientUser;
    }
}
