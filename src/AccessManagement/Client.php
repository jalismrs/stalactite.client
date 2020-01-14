<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\AccessManagement;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\AccessManagement\AuthToken\AuthTokenClient;
use jalismrs\Stalactite\Client\AccessManagement\Customer\CustomerClient;
use jalismrs\Stalactite\Client\AccessManagement\User\UserClient;

class Client extends AbstractClient
{
    public const API_URL_PREFIX = '/access';

    /** @var DomainClient $domainClient */
    private $domainClient;

    /** @var UserClient $userClient */
    private $userClient;

    /** @var CustomerClient $customerClient */
    private $customerClient;

    /** @var RelationClient $relationClient */
    private $relationClient;

    /** @var AuthTokenClient $authTokenClient */
    private $authTokenClient;

    /**
     * @return DomainClient
     */
    public function domains(): DomainClient
    {
        if (!($this->domainClient instanceof DomainClient)) {
            $this->domainClient = new DomainClient($this->apiHost, $this->userAgent);
        }

        return $this->domainClient;
    }

    /**
     * @return UserClient
     */
    public function users(): UserClient
    {
        if (!($this->userClient instanceof UserClient)) {
            $this->userClient = new UserClient($this->apiHost, $this->userAgent);
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
        }

        return $this->customerClient;
    }

    /**
     * @return RelationClient
     */
    public function relations(): RelationClient
    {
        if (!($this->relationClient instanceof RelationClient)) {
            $this->relationClient = new RelationClient($this->apiHost, $this->userAgent);
        }

        return $this->relationClient;
    }

    /**
     * @return AuthTokenClient
     */
    public function authToken(): AuthTokenClient
    {
        if (!($this->authTokenClient instanceof AuthTokenClient)) {
            $this->authTokenClient = new AuthTokenClient($this->apiHost, $this->userAgent);
        }

        return $this->authTokenClient;
    }
}
