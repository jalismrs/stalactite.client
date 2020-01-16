<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement;

use Jalismrs\Stalactite\Client\ClientAbstract;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = '/data';
    
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
     * clientAuthToken
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\AuthToken\Client
     */
    public function getClientAuthToken() : AuthToken\Client
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
     * clientCertificationType
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\CertificationType\Client
     */
    public function getClientCertificationType() : CertificationType\Client
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
     * clientCustomer
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Customer\Client
     */
    public function getClientCustomer() : Customer\Client
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
     * clientDomain
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Domain\Client
     */
    public function getClientDomain() : Domain\Client
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
     * clientPhoneType
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\PhoneType\Client
     */
    public function getClientPhoneType() : PhoneType\Client
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
     * clientPost
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Post\Client
     */
    public function getClientPost() : Post\Client
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
     * clientUser
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\Client
     */
    public function getClientUser() : User\Client
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
