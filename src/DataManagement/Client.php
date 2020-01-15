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
    public function clientAuthToken() : AuthToken\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new AuthToken\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * clientCertificationType
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\CertificationType\Client
     */
    public function clientCertificationType() : CertificationType\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new CertificationType\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * clientCustomer
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Customer\Client
     */
    public function clientCustomer() : Customer\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Customer\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * clientDomain
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Domain\Client
     */
    public function clientDomain() : Domain\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Domain\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * clientPhoneType
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\PhoneType\Client
     */
    public function clientPhoneType() : PhoneType\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new PhoneType\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * clientPost
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Post\Client
     */
    public function clientPost() : Post\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Post\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * clientUser
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\Client
     */
    public function clientUser() : User\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new User\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
}
