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
     * getClientAccessManagement
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Client
     *
     * @throws \InvalidArgumentException
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
     * getClientAuthentification
     *
     * @return \Jalismrs\Stalactite\Client\Authentication\Client
     *
     * @throws \InvalidArgumentException
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
     * getClientDataManagement
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Client
     *
     * @throws \InvalidArgumentException
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
