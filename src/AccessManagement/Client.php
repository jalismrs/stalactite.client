<?php

namespace jalismrs\Stalactite\Client\AccessManagement;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\AccessManagement\AuthToken\AuthTokenClient;
use jalismrs\Stalactite\Client\AccessManagement\Customer\CustomerClient;
use jalismrs\Stalactite\Client\AccessManagement\User\UserClient;

class Client extends AbstractClient
{
    public const API_URL_PREFIX = '/access';

    /** @var UserClient $userClient */
    private $userClient;

    /** @var CustomerClient $customerClient */
    private $customerClient;

    /** @var AuthTokenClient $authTokenClient */
    private $authTokenClient;

    /**
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
     * @return AuthTokenClient
     */
    public function authToken(): AuthTokenClient
    {
        if (!($this->authTokenClient instanceof AuthTokenClient)) {
            $this->authTokenClient = new AuthTokenClient($this->apiHost, $this->userAgent);
            $this->authTokenClient->setHttpClient($this->getHttpClient());
        }

        return $this->authTokenClient;
    }
}