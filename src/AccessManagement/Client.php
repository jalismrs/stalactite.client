<?php

namespace jalismrs\Stalactite\Client\AccessManagement;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\AccessManagement\AuthToken\AuthTokenClient;

class Client extends AbstractClient
{
    public const API_URL_PREFIX = '/access';

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