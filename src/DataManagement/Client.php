<?php

namespace jalismrs\Stalactite\Client\DataManagement;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\AuthToken\AuthTokenClient;
use jalismrs\Stalactite\Client\DataManagement\Customer\CustomerClient;
use jalismrs\Stalactite\Client\DataManagement\User\UserClient;

class Client extends AbstractClient
{
    public const API_URL_PREFIX = '/data';

    /** @var UserClient $userClient */
    private $userClient;

    /** @var CustomerClient $customerClient */
    private $customerClient;

    /** @var DomainClient $domainClient */
    private $domainClient;

    /** @var PostClient $postClient */
    private $postClient;

    /** @var CertificationTypeClient $certificationTypeClient */
    private $certificationTypeClient;

    /** @var PhoneTypeClient $phoneTypeClient */
    private $phoneTypeClient;

    /** @var AuthTokenClient $authTokenClient */
    private $authTokenClient;

    /**
     * @return UserClient
     */
    public function users(): UserClient
    {
        if (!($this->userClient instanceof UserClient)) {
            $this->userClient = new UserClient($this->apiHost, $this->userAgent);
            $this->userClient->setHttpClient($this->getHttpClient());
        }

        return $this->userClient;
    }

    /**
     * @return CustomerClient
     */
    public function customers(): CustomerClient
    {
        if (!($this->customerClient instanceof CustomerClient)) {
            $this->customerClient = new CustomerClient($this->apiHost, $this->userAgent);
            $this->customerClient->setHttpClient($this->getHttpClient());
        }

        return $this->customerClient;
    }

    /**
     * @return DomainClient
     */
    public function domains(): DomainClient
    {
        if (!($this->domainClient instanceof DomainClient)) {
            $this->domainClient = new DomainClient($this->apiHost, $this->userAgent);
            $this->domainClient->setHttpClient($this->getHttpClient());
        }

        return $this->domainClient;
    }

    /**
     * @return PostClient
     */
    public function posts(): PostClient
    {
        if (!($this->postClient instanceof PostClient)) {
            $this->postClient = new PostClient($this->apiHost, $this->userAgent);
            $this->postClient->setHttpClient($this->getHttpClient());
        }

        return $this->postClient;
    }

    /**
     * @return CertificationTypeClient
     */
    public function certificationTypes(): CertificationTypeClient
    {
        if (!($this->certificationTypeClient instanceof CertificationTypeClient)) {
            $this->certificationTypeClient = new CertificationTypeClient($this->apiHost, $this->userAgent);
            $this->certificationTypeClient->setHttpClient($this->getHttpClient());
        }

        return $this->certificationTypeClient;
    }

    /**
     * @return PhoneTypeClient
     */
    public function phoneTypes(): PhoneTypeClient
    {
        if (!($this->phoneTypeClient instanceof PhoneTypeClient)) {
            $this->phoneTypeClient = new PhoneTypeClient($this->apiHost, $this->userAgent);
            $this->phoneTypeClient->setHttpClient($this->getHttpClient());
        }

        return $this->phoneTypeClient;
    }

    public function authToken(): AuthTokenClient
    {
        if (!($this->authTokenClient instanceof AuthTokenClient)) {
            $this->authTokenClient = new AuthTokenClient($this->apiHost, $this->userAgent);
            $this->authTokenClient->setHttpClient($this->getHttpClient());
        }

        return $this->authTokenClient;
    }
}