<?php
declare(strict_types = 1);

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
     * @return \Jalismrs\Stalactite\Client\Data\AuthToken\Client
     */
    public function authToken() : AuthToken\Client
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
     * @return \Jalismrs\Stalactite\Client\Data\CertificationType\Client
     */
    public function certificationType() : CertificationType\Client
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
     * @return \Jalismrs\Stalactite\Client\Data\Customer\Client
     */
    public function customer() : Customer\Client
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
     * @return \Jalismrs\Stalactite\Client\Data\Domain\Client
     */
    public function domain() : Domain\Client
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
     * @return \Jalismrs\Stalactite\Client\Data\PhoneType\Client
     */
    public function phoneType() : PhoneType\Client
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
     * @return \Jalismrs\Stalactite\Client\Data\Post\Client
     */
    public function post() : Post\Client
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
     * @return \Jalismrs\Stalactite\Client\Data\User\Client
     */
    public function user() : User\Client
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
