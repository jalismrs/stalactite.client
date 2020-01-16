<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client
 */
class Client extends
    ClientAbstract
{
    private $clientAccessManagement;
    private $clientAuthentification;
    private $clientDataManagement;
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * clientAccessManagement
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Client
     */
    public function getClientAccessManagement() : AccessManagement\Client
    {
        static $client = null;
        
        if (null === $this->clientAccessManagement) {
            $this->clientAccessManagement = new AccessManagement\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $this->clientAccessManagement;
    }
    
    /**
     * clientAuthentification
     *
     * @return \Jalismrs\Stalactite\Client\Authentication\Client
     */
    public function getClientAuthentification() : Authentication\Client
    {
        static $client = null;
    
        if (null === $this->clientAuthentification) {
            $this->clientAuthentification = new Authentication\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $this->clientAuthentification;
    }
    
    /**
     * clientDataManagement
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Client
     */
    public function getClientDataManagement() : DataManagement\Client
    {
        static $client = null;
    
        if (null === $this->clientDataManagement) {
            $this->clientDataManagement = new DataManagement\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $this->clientDataManagement;
    }
}
