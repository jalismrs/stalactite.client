<?php

namespace jalismrs\Stalactite\Client\AccessManagement;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\AccessManagement\AuthToken\AuthTokenClient;

class Client extends AbstractClient
{
    public const API_URL_PREFIX = '/access';

    /** @var CustomerClient $customerClient */
    private $customerClient;

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