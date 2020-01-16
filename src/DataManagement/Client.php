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
     * getClientAuthToken
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\AuthToken\Client
     *
     * @throws \InvalidArgumentException
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
     * getClientCertificationType
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\CertificationType\Client
     *
     * @throws \InvalidArgumentException
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
     * getClientCustomer
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Customer\Client
     *
     * @throws \InvalidArgumentException
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
     * getClientDomain
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Domain\Client
     *
     * @throws \InvalidArgumentException
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
     * getClientPhoneType
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\PhoneType\Client
     *
     * @throws \InvalidArgumentException
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
     * getClientPost
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Post\Client
     *
     * @throws \InvalidArgumentException
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
     * getClientUser
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\Client
     *
     * @throws \InvalidArgumentException
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
