<?php

namespace jalismrs\Stalactite\Client;

class Client extends AbstractClient
{
    /** @var Authentication\Client $authClient */
    private $authClient;

    /** @var DataManagement\Client $dataManagementClient */
    private $dataManagementClient;

    /** @var AccessManagement\Client $accessManagementClient */
    private $accessManagementClient;

    /**
     * @return Authentication\Client
     */
    public function auth(): Authentication\Client
    {
        if (!($this->authClient instanceof Authentication\Client)) {
            $this->authClient = new Authentication\Client($this->apiHost, $this->userAgent);
        }

        return $this->authClient;
    }

    /**
     * @return DataManagement\Client
     */
    public function data(): DataManagement\Client
    {
        if (!($this->dataManagementClient instanceof DataManagement\Client)) {
            $this->dataManagementClient = new DataManagement\Client($this->apiHost, $this->userAgent);
        }

        return $this->dataManagementClient;
    }

    /**
     * @return AccessManagement\Client
     */
    public function access(): AccessManagement\Client
    {
        if (!($this->accessManagementClient instanceof AccessManagement\Client)) {
            $this->accessManagementClient = new AccessManagement\Client($this->apiHost, $this->userAgent);
        }

        return $this->accessManagementClient;
    }
}